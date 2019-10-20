<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $client app\models\Client */
/* @var $model app\models\forms\EditForm */

$this->title = 'Update Client: ' . $client->name;
$this->params['breadcrumbs'][] = ['label' => 'Clients', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $client->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="client-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
