<?php

class DungeonController extends Controller
{
    public function filters()
    {
        return [
            'accessControl',
        ];
    }

    public function accessRules()
    {
        return [
            ['allow',  // allow all users
                'actions' => ['index'],
                'users' => ['@'],
            ],
            [
                'allow', 'users' => ['*'], 'actions' => ['pollResults'],
            ],
            [
                'allow', 'users' => ['notabenoid'], 'actions' => ['pollRawResults'],
            ],
            ['deny', 'users' => ['*']],
        ];
    }

    public function actionPollResults()
    {
        $Questions = include Yii::app()->basePath.'/components/polls/1.php';

        $res = Yii::app()->db
            ->createCommand('select count(*) n, q_id, answer FROM poll_answers GROUP BY q_id, answer ORDER BY q_id, n desc')
            ->queryAll();
        $data = [];
        foreach ($res as $row) {
            $data[$row['q_id']][] = ['n' => $row['n'], 'answer' => $row['answer']];
        }

        $this->render('pollresults', ['data' => $data, 'Questions' => $Questions]);
    }

    public function actionPollRawResults()
    {
        $res = Yii::app()->db
            ->createCommand('select u.id # 24772 as user_id, u.cdate as user_cdate, u.n_trs, u.rate_t, u.rate_u, u.n_comments, a.q_id, a.cdate, a.ip, a.answer from poll_answers a left join users u on a.user_id = u.id')
            ->queryAll();

        $header = ['ID пользователя', 'Дата регистрации', 'Количество переводов пользователя', 'Рейтинг переводов пользователя', 'Карма пользователя', 'Количество комментариев пользователя', 'Номер вопроса', 'Время ответа', 'IP', 'Ответ'];
        $data = implode(';', $header)."\r\n";

        foreach ($res as $row) {
            $data .= ''.implode(';', $row)."\r\n";
        }

        $data = iconv('utf-8', 'CP1251', $data);

        Yii::app()->request->sendFile('poll-1.csv', $data, 'application/octet-stream', false);

        exit;
    }
}
