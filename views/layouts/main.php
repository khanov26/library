<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\bootstrap\Dropdown;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Главная', 'url' => ['/site/index']],
    ];

    $dropDown = Html::beginTag('li', ['class' => 'dropdown']);
    $dropDown .= '<a href="#" data-toggle="dropdown" class="dropdown-toggle">';
    $dropDown .= '<span class="glyphicon glyphicon-user" aria-hidden="true"></span>';
    if (!Yii::$app->user->isGuest) {
        $dropDown .= '<span style="padding-left: 0.5rem;">' . Yii::$app->user->identity->email . '</span>';
    }
    $dropDown .= '</a>';

    $dropDownItems = [];
    if (Yii::$app->user->isGuest) {
        $dropDownItems[] = ['label' => 'Войти', 'url' => ['/client/login']];
        $dropDownItems[] = ['label' => 'Регистрация', 'url' => ['/client/signup']];
    } else {
        $dropDownItems[] = ['label' => 'Профиль', 'url' => ['/client/edit']];
        $dropDownItems[] = '<li>'
            . '<a href="#">'
            . Html::beginForm(['/client/logout'])
            . Html::submitButton(
                'Выйти',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</a>'
            . '</li>';
    }

    $dropDown.= Dropdown::widget(['items' => $dropDownItems]);
    $dropDown.= Html::endTag('li');

    $menuItems[] = $dropDown;

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
