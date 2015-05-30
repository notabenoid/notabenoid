<?php
    /**
     * @var BlogPost
     */
    $this->pageTitle = $post->isNewRecord ? 'Написать пост' : 'Редактировать пост';
?>
<style type='text/css'>
#BlogPost_body {height:400px;}
</style>
<script type="text/javascript">
var E = {
    rm: function() {
        if(!confirm("Вы уверены?")) return false;

        $("#form-rm").submit();
    }
}
$(E.init);
</script>

<h1><?=$post->isNewRecord ? 'Написать пост' : 'Редактировать пост'; ?></h1>

<form id="form-rm" method="post" action="<?=$post->getUrl('remove'); ?>"><input type="hidden" name="really" value="1"/></form>

<?php
    /** @var TbActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
        'id' => 'form-edit',
        'type' => 'horizontal',
        'htmlOptions' => [
            'enctype' => 'multipart/form-data',
        ],
    ]);

    echo $form->errorSummary($post);

    echo $form->textFieldRow($post, 'title', ['class' => 'span6']);
    echo $form->textAreaRow($post, 'body', ['class' => 'span6', 'hint' => 'Здесь можно использовать некоторые HTML-теги']);
    $topics = Yii::app()->params['blog_topics'][$post->book_id ? 'book' : 'common'];
    if (Yii::app()->user->id != 1) {
        unset($topics[64]);
    }
    echo $form->radioButtonListRow($post, 'topics', $topics);
?>
<div class="form-actions">
    <?php
        echo CHtml::htmlButton("<i class='icon-ok icon-white'></i> Сохранить", ['type' => 'submit', 'class' => 'btn btn-primary']).' ';
        if (!$post->isNewRecord) {
            echo CHtml::htmlButton("<i class='icon-ban-circle icon-white'></i> Удалить", ['onclick' => 'E.rm()', 'class' => 'btn btn-danger']).' ';
        }
        if ($post->isNewRecord) {
            if ($post->book_id) {
                $back = $post->book->getUrl('blog');
            } else {
                $back = '/blog';
            }
        } else {
            $back = $post->url;
        }
        echo CHtml::htmlButton("<i class='icon-remove icon-white'></i> Отмена", ['onclick' => "location.href='{$back}'", 'class' => 'btn btn-success']);
    ?>
</div>
<?php $this->endWidget(); ?>
