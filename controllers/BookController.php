<?php

namespace app\controllers;

use app\models\Book;
use app\models\Client;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BookController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['borrow', 'cancel-borrow', 'bringback'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['borrow', 'cancel-borrow', 'bringback'],
                        'roles' => ['client'],
                    ],
                ],
                'denyCallback' => function () {
                    if (Yii::$app->user->isGuest) {
                        Yii::$app->user->returnUrl = Yii::$app->request->url;
                        return $this->redirect(['/client/login']);
                    }

                    return $this->goHome();
                }
            ],
        ];
    }

    public function actionIndex()
    {
        $books = Book::find()
            ->with(['author', 'genres'])
            ->all();

        return $this->render('index', [
            'books' => $books,
        ]);
    }

    public function actionView(int $id)
    {
        $book = Book::findOne(['id' => $id]);
        if ($book === null) {
            throw new NotFoundHttpException();
        }

        return $this->render('view', [
            'book' => $book,
        ]);
    }

    public function actionBorrow(int $id)
    {
        $book = Book::findOne(['id' => $id]);
        if ($book === null) {
            throw new NotFoundHttpException();
        }

        /** @var Client $client */
        $client = Yii::$app->user->identity;
        if ($book->borrow($client)) {
            Yii::$app->session->setFlash('success', 'Ваш запрос будет рассмотрен администратором');
        } else {
            Yii::$app->session->setFlash('warning', 'На данный момент книга не может быть выдана');
        }

        return $this->redirect(['view', 'id' => $book->id]);
    }

    public function actionCancelBorrow(int $id)
    {
        $book = Book::findOne(['id' => $id]);
        if ($book === null) {
            throw new NotFoundHttpException();
        }

        /** @var Client $client */
        $client = Yii::$app->user->identity;
        if ($book->cancelBorrow($client)) {
            Yii::$app->session->setFlash('success', 'Запрос отменен');
        } else {
            Yii::$app->session->setFlash('warning', 'Запрос не может быть отменен');
        }

        return $this->redirect(['view', 'id' => $book->id]);
    }

    public function actionBringback(int $id)
    {
        $book = Book::findOne(['id' => $id]);
        if ($book === null) {
            throw new NotFoundHttpException();
        }
        /** @var Client $client */
        $client = Yii::$app->user->identity;
        if ($book->bringBack($client)) {
            Yii::$app->session->setFlash('success', 'Вы вернули книгу');
        } else {
            Yii::$app->session->setFlash('warning', 'Книгу не удалось вернуть');
        }

        return $this->redirect(['view', 'id' => $book->id]);
    }
}
