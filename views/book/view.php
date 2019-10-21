<?php

/* @var $this yii\web\View */
/* @var $book app\models\Book */


$this->title = $book->name;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

use yii\helpers\Html;?>

<div class="row">
    <div class="col-lg-6">
        <dl class="row">
            <dt class="col-sm-4">Название</dt>
            <dd class="col-sm-8"><?= $book->name ?></dd>

            <dt class="col-sm-4">Автор</dt>
            <dd class="col-sm-8"><?= $book->author->name ?></dd>

            <dt class="col-sm-4">Жанр</dt>
            <dd class="col-sm-8">
                <?php
                    echo implode(', ', array_map(function ($genre) {
                        return $genre->name;
                    }, $book->genres));
                ?>
            </dd>

            <dt class="col-sm-4">Год издание</dt>
            <dd class="col-sm-8"><?= $book->year ?></dd>
        </dl>

        <div class="row">
            <div class="col-12">
                <?php
                if (!Yii::$app->user->isGuest) {
                    if (!$book->isBorrowed()) {
                        echo Html::a('Запросить книгу', ['borrow', 'id' => $book->id], [
                            'class' => 'btn btn-success',
                        ]);
                    }
                    if ($book->canBeCanceled(Yii::$app->user->identity)) {
                        echo Html::a('Отменить запрос', ['cancel-borrow', 'id' => $book->id], [
                            'class' => 'btn btn-success',
                        ]);
                    }
                    if ($book->canBeBroughtBack(Yii::$app->user->identity)) {
                        echo Html::a('Вернуть книгу', ['bringback', 'id' => $book->id], [
                            'class' => 'btn btn-success',
                        ]);
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
