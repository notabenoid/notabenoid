<?php
    $this->pageTitle = 'Переводчики';
?>

<style type='text/css'>
	.grid-view {padding-top:0 !important;}
	.grid-view table.items td.r, .grid-view table.items th.r {background:#eee; font-weight:bold;} /* колонка, по которой идёт сортировка */
</style>

<h1>Рейтинг переводчиков</h1>

<?php
    $this->widget('bootstrap.widgets.TbGridView', [
        'dataProvider' => $users_dp,
        'type' => 'stripped condensed',
        'template' => '{pager} {items} {pager}',
        'columns' => [
            ['value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)', 'type' => 'text', 'header' => ''],
            ['value' => '$data->ahref', type => 'html', 'header' => 'ник', 'headerHtmlOptions' => ['class' => 'm']],
            ['name' => 'rate_u', type => 'number', 'header' => 'карма'],
            ['name' => 'n_trs', type => 'number', 'header' => 'количество переводов'],
            ['name' => 'rate_t', type => 'number', 'header' => 'суммарный рейтинг'],
            ['value' => '$data->n_trs ? sprintf("%.02f", $data->rate_t / $data->n_trs) : ""', 'header' => 'средний рейтинг перевода'],
        ],
    ]);
?>
