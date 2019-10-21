<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Borrow;
use app\modules\admin\models\BorrowSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * BorrowController implements the CRUD actions for Borrow model.
 */
class BorrowController extends Controller
{
    /**
     * Lists all Borrow models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BorrowSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionResolve(int $id)
    {
        $borrow = $this->findModel($id);
        $borrow->status = Borrow::STATUS_RESOLVED;
        if ($borrow->save()) {
            Yii::$app->session->setFlash('success', 'Запрос клиента потвержден. Книга выдана.');
        } else {
            Yii::$app->session->setFlash('danger', 'Произошла ошибка');
        }

        return $this->redirect(['index']);
    }

    public function actionReject(int $id)
    {
        $borrow = $this->findModel($id);
        $borrow->status = Borrow::STATUS_REJECTED;
        $borrow->brought_time = time();
        if ($borrow->save()) {
            Yii::$app->session->setFlash('success', 'Запрос клиента отклонен');
        } else {
            Yii::$app->session->setFlash('danger', 'Произошла ошибка');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Borrow the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Borrow::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
