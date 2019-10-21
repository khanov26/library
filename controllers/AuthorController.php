<?php

namespace app\controllers;

use app\models\Author;
use yii\web\Controller;

class AuthorController extends Controller
{

    /**
     * Поиск автора по имени (автодополнение при создании и редактировании книги)
     *
     * @param string $term
     * @return \yii\web\Response
     */
    public function actionSearch(string $term)
    {
        $authors = Author::find()->select('name')->where(['like', 'name', $term])->limit(10)->column();

        return $this->asJson($authors);
    }
}
