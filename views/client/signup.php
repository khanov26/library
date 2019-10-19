<?php

/* @var $this yii\web\View */
/* @var $model app\models\forms\SignupForm */
/* @var $form ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="user-signup">

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin([
                'id' => 'signup-form',
            ]); ?>

            <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'email')->textInput() ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'passwordConfirm')->passwordInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Зарегистрировать', ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>

