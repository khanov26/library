<?php

namespace app\controllers;

use app\models\Client;
use app\models\forms\EditForm;
use app\models\forms\LoginForm;
use app\models\forms\SignupForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class ClientController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['login', 'signup', 'edit'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['edit'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['login', 'signup'],
                        'roles' => ['?'],
                    ]
                ],
                'denyCallback' => function () {
                    if (Yii::$app->user->isGuest) {
                        Yii::$app->user->returnUrl = Yii::$app->request->url;
                        return $this->redirect(['login']);
                    }

                    return $this->goHome();
                }
            ],
        ];
    }

    public function actionSignup()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post()) && $client = $model->signup()) {
            Yii::$app->user->login($client);
            Yii::$app->session->setFlash('success', 'Спасибо за регистрацию');

            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionEdit()
    {
        $model = new EditForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->goHome();
        }

        $model->loadCurrentData();
        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        $this->goHome();
    }

    public function actionBooks()
    {
        /** @var Client $client */
        $client = Yii::$app->user->identity;
        $clientBooks = $client->getBooks();

        return $this->render('/book/index', [
            'books' => $clientBooks,
        ]);
    }
}
