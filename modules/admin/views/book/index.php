<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\BookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">
    <a href="<?= Url::to(['/admin']) ?>" class="btn btn-primary">
        <span class="glyphicon glyphicon-arrow-left"></span>
        <span style="padding-left: 0.5rem;">На главную</span>
    </a>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Book', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'year',
            [
                'label' => 'Автор',
                'attribute' => 'author.name',
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]) ?>
</div>
