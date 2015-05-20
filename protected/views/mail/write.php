<?php
    /**
     * @var Mail
     * @var Mail или null
     */
    $this->pageTitle = $reply ? 'Ответить' : 'Написать письмо';
?>
<style type="text/css">
#Mail_body {height:300px;}
</style>
<script type="text/javascript">
var W = {
	is_reply: false,
	init: function() {
		$("#form-write " + (W.is_reply ? "#Mail_body" : "#Mail_sendTo")).focus();

		$("#form-write #Mail_body").keyup(function(e) {
			if(e.ctrlKey && e.which == 13) {
				if(confirm("Отправить письмо?")) $("#form-write").submit();
			}
		});
	}
};
<?php
    if ($reply) {
        echo "W.is_reply=true;\n";
    }
?>
$(W.init);
</script>

<?php
    echo '<h1>'.($reply ? 'Ответить' : 'Написать письмо').'</h1>';

    /** @var TbActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
        'id' => 'form-write',
        'type' => 'horizontal',
    ]);

    echo $form->errorSummary($message);

    echo $form->textFieldRow($message, 'sendTo', ['class' => 'span6']);

    echo $form->textFieldRow($message, 'subj', ['class' => 'span6']);

    if ($reply) {
        $quote = htmlspecialchars_decode($reply->body);
        $quote = '> '.str_replace("\n", "\n> ", $quote);
        $message->body = $quote;
    }
    echo $form->textAreaRow($message, 'body', ['class' => 'span6', 'hint' => 'Здесь можно использовать некоторые HTML-теги']);
?>
<div class="form-actions">
<?php
    echo CHtml::htmlButton("<i class='icon-ok icon-white'></i> Отправить", ['type' => 'submit', 'class' => 'btn btn-primary']).' ';
    $back = $reply ? $reply->url : '/my/mail';
    echo CHtml::htmlButton("<i class='icon-remove icon-white'></i> Отмена", ['onclick' => "location.href='{$back}'", 'class' => 'btn btn-success']);
?>
</div>
<?php $this->endWidget(); ?>
