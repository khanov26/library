<?php

/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'Библиотека';

?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Библиотека</h1>
        <p><a class="btn btn-lg btn-success" href="<?= Url::to(['/book']) ?>">Перейти к списку книг</a></p>
    </div>
</div>
