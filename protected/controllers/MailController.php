<?php

class MailController extends Controller
{
    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
        ];
    }

    public function accessRules()
    {
        return [
            ['allow',
                'actions' => ['index', 'message', 'write'],
                'users' => ['@'],
            ],
            ['allow',
                'actions' => ['spam'],
                'users' => ['notabenoid'],
            ],
            ['deny', 'users' => ['*']],
        ];
    }

    public function actionIndex()
    {
        if ($_POST['act'] == 'rm' || $_POST['act'] == 'seen' || $_POST['act'] == 'unseen') {
            if (!is_array($_POST['id'])) {
                $this->redirect('/my/mail');
            }

            $in = '';
            $params = [':user_id' => Yii::app()->user->id];
            foreach ($_POST['id'] as $k => $v) {
                $p = ':id'.(int) $k;
                if ($in) {
                    $in .= ', ';
                }
                $in .= $p;
                $params[$p] = (int) $v;
            }

            if ($_POST['act'] == 'rm') {
                Yii::app()->db->createCommand("DELETE FROM mail WHERE user_id = :user_id AND id IN({$in})")->execute($params);
            } elseif ($_POST['act'] == 'seen') {
                Yii::app()->db->createCommand("UPDATE mail SET seen = true WHERE user_id = :user_id AND id IN({$in})")->execute($params);
            } elseif ($_POST['act'] == 'unseen') {
                Yii::app()->db->createCommand("UPDATE mail SET seen = false WHERE user_id = :user_id AND id IN({$in})")->execute($params);
            }

            $this->redirect('/my/mail?'.$_SERVER['QUERY_STRING']);
        }

        $crit = new CDbCriteria();

        $folder = (int) $_GET['folder'];
        if (!isset(Mail::$folders[$folder])) {
            $folder = Mail::INBOX;
        }

        if ($_GET['new']) {
            $crit->addCondition('NOT t.seen');
        }

        $mail_dp = new CActiveDataProvider(Mail::model()->folder(Yii::app()->user->id, $folder)->with('buddy'), [
            'pagination' => ['pageSize' => 30],
            'criteria' => $crit,
        ]);

        $this->side_view = ['index_side' => ['folder' => $folder]];
        $this->render('index', ['mail_dp' => $mail_dp, 'folder' => $folder]);
    }

    public function actionMessage($id)
    {
        $message = Mail::model()->with('buddy')->findByPk((int) $id);
        if (!$message || $message->user_id != Yii::app()->user->id) {
            throw new CHttpException(404, 'Письма не существует.');
        }

        $message->setSeen();

        $this->side_view = ['message_side' => ['message' => $message]];
        $this->render('message', ['message' => $message]);
    }

    public function actionWrite()
    {
        if (isset($_GET['reply'])) {
            $reply = Mail::model()->with('buddy')->findByPk((int) $_GET['reply'], 't.user_id = :user_id', [':user_id' => Yii::app()->user->id]);
            $reply->setSeen();
        } else {
            $reply = null;
        }

        $message = new Mail();
        if ($reply) {
            $message->sendTo = $reply->buddy->login;
            if (preg_match('/^Re: (.*)$/', $reply->subj, $res)) {
                $subj = "Re[1]: {$res[1]}";
            } elseif (preg_match('/^Re\[(\d+)\]: (.*)$/', $reply->subj, $res)) {
                $subj = 'Re['.($res[1] + 1).']: '.$res[2];
            } else {
                $subj = "Re: {$reply->subj}";
            }
            $message->subj = $subj;
        } elseif (isset($_GET['to'])) {
            $message->sendTo = trim(strip_tags($_GET['to']));
        }

        if (isset($_POST['Mail'])) {
            $message->setAttributes($_POST['Mail']);
            if ($message->send()) {
                Yii::app()->user->setFlash('success', 'Письмо отправлено '.$message->buddy->ahref);
                $this->redirect('/my/mail');
            }
        }

        if (!$reply) {
            $buddies = User::model()->findAll([
                'select' => ['t.id', 't.login', 't.sex', 't.upic'],
                'join' => 'RIGHT JOIN mail ON t.id = mail.buddy_id',
                'condition' => 'mail.user_id = :user_id',
                'group' => 't.id',
                'order' => 'COUNT(*) DESC',
                'limit' => 20,
                'params' => [':user_id' => Yii::app()->user->id],
            ]);

            $this->side_view = ['write_side' => ['message' => $message, 'buddies' => $buddies]];
        }
        $this->render('write', ['message' => $message, 'reply' => $reply]);
    }
}
