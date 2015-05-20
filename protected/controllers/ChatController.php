<?php

class ChatController extends Controller
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
                'actions' => ['index', 'room'],
                'users' => ['*'],
            ],
            ['allow', // allow authenticated user
                'actions' => ['clear'],
                'users' => ['@'],
            ],
            ['deny',  // deny all users
                'users' => ['*'],
            ],
        ];
    }

    public function actionRoom($room_id)
    {
        $since = intval(isset($_POST['since']) ? $_POST['since'] : $_GET['since']);
        $room_id = (int) $room_id;
        $key = "chat{$room_id}";

        $room = Yii::app()->cache->get($key);
        if (!is_array($room)) {
            $room = [];
        }

        if (Yii::app()->request->isPostRequest) {
            $msg = trim($_POST['msg']);

            $h = date('h');
            $m = date('i');
            if ($h == 4 && $m >= 20 && $m <= 40) {
                $p = new CHtmlPurifier();
                $p->options = Yii::app()->params['HTMLPurifierOptions'];
                $msg = trim($p->purify($msg));
            } else {
                $msg = strip_tags($msg);
            }

            if ($msg != '') {
                $msg = mb_substr($msg, 0, 2048);
                $msg = Yii::app()->parser->parse($msg);
                $line = [
                    'u' => Yii::app()->user->login,
                    'i' => Yii::app()->user->id,
                    't' => time(),
                    'm' => $msg,
                ];
                array_push($room, $line);
                if (count($room) > 50) {
                    array_shift($room);
                }
            }

            Yii::app()->cache->set($key, $room, 60 * 60 * 24 * 3);
        }

        if ($since > 0) {
            $roomGood = [];
            foreach ($room as $k => $v) {
                $room['m'] .= " <small>(after {$since})</small>";
                if ($v['t'] > $since) {
                    $roomGood[] = $room[$k];
                }
            }
            $room = $roomGood;
        }

        echo json_encode(['room' => $room, 'servertime' => time()]);
    }

    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionClear()
    {
        Yii::app()->cache->set('chat1', [], 60 * 60 * 24 * 7);
        $this->redirect('/chat');
    }
}
