<?php

/** @var \yii\web\View $this */
/** @var app\models\forms\EditForm $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="row">
    <div class="col-lg-5">
        <?php $form = ActiveForm::begin(['id' => 'edit-form']); ?>
        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'currentPassword')->passwordInput() ?>
        <?= $form->field($model, 'newPassword')->passwordInput() ?>
        <?= $form->field($model, 'newPasswordConfirm')->passwordInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
