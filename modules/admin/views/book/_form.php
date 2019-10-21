<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\forms\BookForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $allGenres array */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'author')->widget(AutoComplete::class, [
        'clientOptions' => [
            'source' => '/author/search',
            'minLength' => 3,
        ],
    ])->textInput() ?>

    <?php $allGenres = Json::encode($allGenres) ?>
    <?= $form->field($model, 'genres')->widget(AutoComplete::class, [
        'clientOptions' => [
            'source' => new JsExpression("function( request, response ) {
                            response( $.ui.autocomplete.filter(
                                 $allGenres, request.term.split( /,\s*/ ).pop() ) );
                        }"),
            'select' => new JsExpression('function( event, ui ) {
                            var terms = this.value.split( /,\s*/ );
                            // remove the current input
                            terms.pop();
                            // add the selected item
                            terms.push( ui.item.value );
                            // add placeholder to get the comma-and-space at the end
                            terms.push( "" );
                            this.value = terms.join( ", " );
                            return false;
                        }'),
            'focus' => new JsExpression('function() {
                            // prevent value inserted on focus
                            return false;
                        }'),
        ],
    ])->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
