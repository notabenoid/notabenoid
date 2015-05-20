<?php
    /**
     * @var Comment
     * @var bool    - не выводить ссылку "ответить", иначе - если не гость
     * @var bool    - не выводить ссылку удалить, иначе - $comment->can("delete")
     * @var bool    - не выводить точку
     * @var bool    - не выводить ссылку на родительский комментарий
     * @var string  - допишется в #meta
     */
    $class = 'comment';
    if ($comment->user_id !== '') {
        $class .= " u{$comment->user_id}";
    }
    if ($comment->isDeleted()) {
        $class .= ' deleted';
    }
    if ($comment->is_new and $comment->user_id != Yii::app()->user->id) {
        $class .= ' new';
        echo "<a name='cmt_new'></a>";
    }
    echo "<div class='{$class}' id='cmt_{$comment->id}'>";

    if ($comment->isDeleted()) {
        echo "<div class='content'>Удалённый комментарий.</div>";
    } else {
        echo "<div class='content'>";
        echo Yii::app()->parser->parse($comment->body);
        echo '</div>';

        echo "<div class='meta'>";
        echo "<a name='cmt_{$comment->id}' class='a' href='#cmt_{$comment->id}'></a>";

        echo $comment->user_id ? $comment->author->ahref : 'Анонимно';
        echo ' &ndash; '.Yii::app()->dateFormatter->formatDateTime($comment->cdate, 'medium', 'short').' | ';

            // if($comment->can("reply")) ...
            if (!$disable_reply and !Yii::app()->user->isGuest) {
                echo "<a href='#cmt_{$comment->id}' class='re'>ответить</a> | ";
            }

        if (!$disable_delete and $comment->can('delete')) {
            echo "<a href='#' class='rm'>удалить</a> | ";
        }

        if (!$disable_dot) {
            echo "<a href='#' class='dot'>☼</a> ";
        }
        if (!$disable_up && $comment->pid) {
            echo "<a href='#cmt_{$comment->pid}' class='up'>▵</a> ";
        }

        echo $meta_extra;
        echo '</div>';
    }

    echo '</div>';
