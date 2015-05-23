<?php

class BlogController extends Controller
{
    public $siteArea = 'blog';

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
                'actions' => ['index', 'post'],
                'users' => ['*'],
            ],
            ['allow', // allow authenticated user
                'actions' => ['talks', 'talks_ini', 'comment_reply', 'comment_remove', 'comment_rate', 'edit', 'remove'],
                'users' => ['@'],
            ],
            ['deny',  // deny all users
                'users' => ['*'],
            ],
        ];
    }

    public function actionIndex()
    {
        $topics = [];

        if (isset($_GET['topic'])) {
            $topic = (int) $_GET['topic'];
            if (!isset(Yii::app()->params['blog_topics']['common'][$topic])) {
                $this->redirect('/blog');
            }
            $topics = [$topic];
        } elseif (isset($_GET['topics'])) {
            if ($_GET['topics'] == 'all') {
                $topics = [];
            } elseif (is_array($_GET['topics'])) {
                foreach ($_GET['topics'] as $t) {
                    $t = (int) $t;
                    if (isset(Yii::app()->params['blog_topics']['common'][$t])) {
                        $topics[] = $t;
                    }
                }
            }
        } else {
            $T = explode('.', Yii::app()->user->ini['blog.topics']);
            foreach ($T as $t) {
                $t = (int) $t;
                if (isset(Yii::app()->params['blog_topics']['common'][$t])) {
                    $topics[] = $t;
                }
            }
        }
        if ($topics == array_keys(Yii::app()->params['blog_topics']['common'])) {
            $topics = [];
        }

        Yii::app()->user->ini['blog.topics'] = implode('.', $topics);
        Yii::app()->user->ini->save();

        $lenta = new CActiveDataProvider(BlogPost::model()->common($topics), [
            'pagination' => ['pageSize' => 20],
        ]);

        $where = ['book_id IS NULL'];
        if ($topics) {
            $where[] = 'topics IN('.(implode(',', $topics)).')';
        } else {
            $where[] = 'topics IN('.implode(',', array_keys(Yii::app()->params['blog_topics']['common'])).')';
        }
        $lenta->totalItemCount = Yii::app()->db->cache(60 * 60)->createCommand('SELECT COUNT(*) FROM blog_posts WHERE '.implode(' AND ', $where))->queryScalar();

        $this->side_view = ['index_side' => ['topics' => $topics]];
        $this->render('index', ['lenta' => $lenta]);
    }

    public function actionPost($post_id)
    {
        $post_id = (int) $post_id;
        $post = BlogPost::model()->with('author', 'seen')->findByPk($post_id);

        if (!$post) {
            throw new CHttpException(404, 'Поста не существует. Возможно, он удалён.');
        }
        if ($post->book_id != 0) {
            $this->redirect("/book/{$post->book_id}/blog/{$post->id}");
        }
        if (!isset(Yii::app()->params['blog_topics']['common'][$post->topics])) {
            $this->redirect('/blog');
        }

        $comments = Comment::model()->with('author')->post($post_id)->newer($post->seen->seen)->findAll();

        $post->setSeen();

        $this->side_view = ['index_side' => ['topic' => $post->topics]];
        $this->render('post', ['post' => $post, 'comments' => $comments]);
    }

    public function actionComment_reply($post_id, $comment_id = 0)
    {
        $post_id = (int) $post_id;
        $comment_id = (int) $comment_id;
        if (!isset($_POST['Comment'])) {
            $this->redirect("/blog/{$post_id}");
        }

        if ($comment_id) {
            $parent = Comment::model()->with('post', 'author')->findByPk($comment_id);
            if (!$parent) {
                throw new CHttpException(404, 'Вы пытаетесь ответить на несуществующий комментарий');
            }
        } else {
            $parent = new Comment();
            $parent->post = BlogPost::model()->with('author', 'seen')->findByPk($post_id);
            $parent->post_id = $parent->post->id;
        }

        /* @todo тут бы хорошо проверять права доступа в пост */

        $comment = new Comment();
        $comment->setAttributes($_POST['Comment']);

        if ($parent->reply($comment)) {
            $parent->post->afterCommentAdd($comment, $parent);
        } else {
            Yii::app()->user->setFlash('error', $comment->getErrorsString());
        }

        if ($_POST['ajax']) {
            if (Yii::app()->user->hasFlash('error')) {
                echo json_encode(['error' => Yii::app()->user->getFlash('error')]);
            } else {
                $comment->is_new = true;
                echo json_encode([
                    'id' => $comment->id, 'pid' => $comment->pid,
                    'html' => $this->renderPartial('//blog/_comment-1', ['comment' => $comment], true),
                ]);
            }
        } else {
            $this->redirect($parent->post->url.'#cmt_'.$comment->id);
        }
    }

    public function actionComment_remove($post_id, $comment_id)
    {
        $post_id = (int) $post_id;
        $comment_id = (int) $comment_id;
        if (!Yii::app()->request->isPostRequest) {
            $this->redirect("/blog/{$post_id}");
        }

        $json = ['id' => $comment_id];
        $user = Yii::app()->user;

        // Загружаем удаляемый комментарий вместе с постом
        $comment = Comment::model()->with('post')->findByPk($comment_id);
        if (!$comment) {
            $json['error'] = 'Вы пытаетесь удалить несуществующий комментарий. Бросьте, пустое.';
        } else {
            // Права доступа: свой комментарий, в моём посте, модератор блога
            if (!$comment->can('delete')) {
                $json['error'] = 'Вы не можете удалить этот комментарий.';
            }

            // Удаляем комментарий
            elseif ($comment->delete()) {
                $comment->post->afterCommentRm($comment);
            } else {
                $json['error'] = 'Не получилось удалить комментарий :(';
            }
        }

        echo json_encode($json);
    }

    public function actionComment_rate($post_id, $comment_id)
    {
        $post_id = (int) $post_id;
        $comment_id = (int) $comment_id;
        if (!Yii::app()->request->isPostRequest) {
            throw new CHttpException(400, '');
        }

        /** @var Comment $comment */
        $comment = Comment::model()->with('post')->findByPk($comment_id);
        if (!$comment) {
            throw new CHttpException(404, 'Комментарий удалён.');
        }
        if ($comment->post_id != $post_id) {
            throw new CHttpException(400, '');
        }
        if (!$comment->post->can('read')) {
            throw new CHttpException(403, 'У вас нет доступа в этот пост.');
        }
        if (!$comment->can('rate')) {
            throw new CHttpException(403, 'Вы не можете оценивать этот комментарий.');
        }

        $comment->rate((int) $_POST['mark']);

        echo json_encode([
            'id' => $comment->id,
            'rating' => $comment->rating,
            'n_votes' => $comment->n_votes,
        ]);
    }

    public function actionEdit($post_id = 0)
    {
        $post_id = (int) $post_id;
        if ($post_id != 0) {
            $post = BlogPost::model()->findByPk($post_id);
            if (!$post) {
                throw new CHttpException(404, 'Поста не существует. Возможно, его удалили');
            }
            if ($post->user_id != Yii::app()->user->id and !Yii::app()->user->can('blog_moderate')) {
                throw new CHttpException(403, 'Вы можете редактировать только собственные посты');
            }
            if ($post->book_id != 0) {
                $this->redirect("/book/{$post->book_id}/blog/{$post->id}/edit");
            }
        } else {
            $post = new BlogPost();
            $post->user_id = Yii::app()->user->id;
            $post->topics = (int) $_GET['topic'];
        }

        if (isset($_POST['BlogPost'])) {
            $post->attributes = $_POST['BlogPost'];
            $post->lastcomment = date('Y-m-d H:i:s');
            if ($post->save()) {
                $post->setTrack();
                $this->redirect($post->url);
            }
        }

        $this->render('edit', ['post' => $post]);
    }

    public function actionRemove($post_id)
    {
        $post_id = (int) $post_id;
        if (!$_POST['really']) {
            $this->redirect('/blog');
        }

        $post = BlogPost::model()->findByPk($post_id);
        if (!$post) {
            throw new CHttpException(404, 'Поста не существует. Возможно, его уже удалили.');
        }
        if ($post->user_id != Yii::app()->user->id and !Yii::app()->user->can('blog_moderate')) {
            throw new CHttpException(403, 'Вы можете удалять только собственные посты');
        }

        $post->delete();

        $this->redirect('/blog');
    }
}
