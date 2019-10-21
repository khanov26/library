<?php

/* @var $this yii\web\View */
/* @var $books app\models\Book[] */

use yii\helpers\Html;

$this->title = 'Книги';
$this->params['breadcrumbs'][] = $this->title;

?>

<table class="table table-striped">
    <tr>
        <th>Название</th>
        <th>Автор</th>
        <th>Жанр</th>
    </tr>
    <?php foreach ($books as $book): ?>
        <tr>
            <td><?= Html::a($book->name, ['/book/view', 'id' => $book->id]) ?></td>
            <td><?= $book->author->name ?></td>
            <td>
                <?php foreach ($book->genres as $genre): ?>
                    <span class="clearfix"><?= $genre->name ?></span>
                <?php endforeach; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
