<?php

use app\models\Borrow;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\BorrowSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Запросы на выдачу книг';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="borrow-index">
    <a href="<?= Url::to(['/admin']) ?>" class="btn btn-primary">
        <span class="glyphicon glyphicon-arrow-left"></span>
        <span style="padding-left: 0.5rem;">На главную</span>
    </a>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'label' => 'Книга',
                'attribute' => 'book.name',
            ],
            [
                'label' => 'Клиент',
                'attribute' => 'client.name',
            ],
            'taken_time:datetime',
            'brought_time:datetime',
            [
                'label' => 'Статус',
                'attribute' => 'status',
                'filter' => Borrow::STATUS_LABELS,
                'value' => function ($model, $key, $index, $column) {
                    return Borrow::STATUS_LABELS[$model->status];
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{resolve} {reject}',
                'buttons' => [
                    'resolve' => function ($url, $model, $key) {
                        return $model->status === Borrow::STATUS_PENDING ?
                            Html::a('Потвердить', $url, ['class' => 'btn btn-block btn-success']) : '';
                    },
                    'reject' => function ($url, $model, $key) {
                        return $model->status === Borrow::STATUS_PENDING ?
                            Html::a('Отклонить', $url, ['class' => 'btn btn-block btn-danger']) : '';
                    },
                ],
            ],
        ],
    ]) ?>
</div>
