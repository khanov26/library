<?php

use yii\helpers\Html;

?>
<div class="admin-default-index">
    <?= Html::a('Запросы', ['borrow/index'], ['class' => 'btn btn-success']) ?>
    <?= Html::a('Книги', ['book/index'], ['class' => 'btn btn-success']) ?>
    <?= Html::a('Клиенты', ['client/index'], ['class' => 'btn btn-success']) ?>
</div>
