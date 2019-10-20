<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\forms\SignupForm|app\models\forms\EditForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="client-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->label('Имя')->textInput(['autofocus' => true]) ?>
    <?= $form->field($model, 'email')->label('Email')->textInput() ?>
    <?php if ($model->canGetProperty('password')) {
        // SignupForm
        echo $form->field($model, 'password')->label('Пароль')->passwordInput();
    } else {
        // EditForm
        echo $form->field($model, 'newPassword')->label('Пароль')->passwordInput();
    }?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
