<?php

/* @var $this yii\web\View */
/* @var $model app\models\forms\LoginForm */
/* @var $form ActiveForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Вход на сайт';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-lg-5">
        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'rememberMe')->checkbox() ?>

        <div class="form-group">
            <?= Html::submitButton('Войти', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
