<div class="post topic-<?=$post->topics; ?>" id="post_<?=$post->id; ?>">
<?php
    /**
     * Переменные снаружи.
     *
     * @var BlogPost
     * @var string   "index", "post", "talks", "user" - размещение поста (лента, страница поста, мои обсуждения, посты пользователя)
     * @var array    array("edit" => boolean, "mytalks" => boolean, "bookLink" => boolean)
     */
    $user = Yii::app()->user;

    if (!is_array($has)) {
        $has = [];
    }
    $has = array_merge([
        'edit' => $post->can('edit'),
        'mytalks' => !$user->isGuest,
        'bookLink' => true,
    ], $has);

    $T = [];
    if ($has['edit']) {
        $T[] = "<a href='".$post->getUrl('edit')."'>править</a>";
    }
    if ($placement == 'talks') {
        $T[] = "<a href='/my/comments/rm/?post_id={$post->id}' class='talks'>не показывать</a>";
    } else {
        if (!$user->isGuest && $has['mytalks']) {
            if (!$post->seen->track) {
                $T[] = "<a href='/my/comments/add?post_id={$post->id}' onclick='return Blog.my({$post->id}, this)' class='talks'>в мои обсуждения</a>";
            } else {
                $T[] = "<a href='/my/comments/?mode=p#post_{$post->id}' title='Пост в ваших обсуждениях' class='talks'>&rarr;</a>";
            }
        }
    }

    $tag = $placement == 'post' ? 'h1' : 'h2';
    if ($post->title != '') {
        echo "<{$tag}><a href='{$post->url}'>{$post->title}</a></{$tag}>";
    }
    if ($post->book_id) {
        $Q = [];
        if ($post->isAnnounce) {
            $Q[] = $post->topicHtml;
        }
        if ($has['bookLink']) {
            $Q[] = "<a href='".$post->book->getUrl('blog')."'>{$post->book->s_title}</a>";
        }

        if (count($Q)) {
            echo "<p class='book'>";
            echo implode(' ', $Q);
            echo '</p>';
        }
    }

    echo "<div class='body'>".Yii::app()->parser->out($post->body)."</div>\n";

    echo "<div class='info'>";

    echo "<span class='author'>";
    $A = ['m' => 'Написал', 'f' => 'Написала', 'x' => 'Написало'];
    echo $A[$post->author->sex].' '.$post->author->ahref;
    echo '</span> ';

    echo "<span class='date'>";
    echo Yii::app()->dateFormatter->formatDateTime($post->cdate, 'medium', 'short');
    echo '</span> ';

    echo "<span class='topic'>";
    echo "<a href='/blog/?topics[]={$post->topics}'>".Yii::app()->params['blog_topics'][$post->book_id ? 'book' : 'common'][$post->topics].'</a>';
    echo '</span> ';

    if ($has['extra'] != '') {
        echo "<span class='extra'>".$has['extra'].'</span>';
    }

    echo "<span class='cmt'>";
    if ($post->n_comments != 0) {
        echo " <a href='{$post->url}#Comments'>".Yii::t('app', '{n} комментарий|{n} комментария|{n} комментариев', $post->n_comments).'</a>';
        if ($post->n_new_comments != 0) {
            echo " / <a href='{$post->url}#cmt_new' class='new'>".Yii::t('app', '{n} новый|{n} новых|{n} новых', $post->n_new_comments).'</a>';
        }
    } else {
        echo "<a href='{$post->url}#Comments'>комментировать</a>";
    }
    echo '</span> ';

    echo "<span class='tools'>";
    echo implode(' | ', $T);
    echo '</span> ';

    echo '</div>';
?>
</div>
