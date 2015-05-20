<?php
    /**
     * @var Chapter
     * @var GenOptions
     * @var ReadyGenerator_base
     */
    $this->pageTitle = "Готовый перевод {$chap->book->fullTitle}: {$chap->title}";
?>
<h1><?php echo "Готовый перевод {$chap->book->fullTitle}: {$chap->title}"; ?></h1>
<?php
    $generator->generate(false);
?>
