<?php

namespace app\models\forms;

use app\models\Client;
use Yii;
use yii\base\Model;

class EditForm extends Model
{
    public $name;
    public $email;
    public $currentPassword;
    public $newPassword;
    public $newPasswordConfirm;

    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'email' => 'Адрес электронной почты',
            'currentPassword' => 'Текущий пароль',
            'newPassword' => 'Новый пароль',
            'newPasswordConfirm' => 'Повторите новый пароль',

        ];
    }

    public function rules()
    {
        return [
            [['name', 'email'], 'trim'],

            [['name', 'email'], 'required', 'message' => 'Необходимо заполнить это поле'],

            ['name', 'string', 'min' => 2, 'max' => 255],

            ['email', 'email'],

            ['email', 'unique', 'targetClass' => Client::class, 'when' => function($model, $attribute) {
                /** @var Client $client */
                $client = Yii::$app->user->identity;
                return $client->$attribute !== $model->$attribute;
            }, 'message' => 'Адрес уже используется'],

            ['currentPassword', 'required', 'when' => function($model, $attribute) {
                return !empty($model->newPassword);
            }, 'whenClient' => 'function(attribute, value) {
                return $("#editform-newpassword").val() !== "";
            }'],

            [['currentPassword', 'newPassword'], 'string', 'min' => 6, 'max' => 255,
                'tooShort' => 'Пароль должен содержать минимум 6 символов'],
            ['currentPassword', 'validatePassword'],

            ['newPasswordConfirm', 'compare', 'compareAttribute' => 'newPassword', 'skipOnEmpty' => false,
                'message' => 'Пароли не совпадают'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        /** @var Client $user */
        $user = Yii::$app->user->identity;

        if (!$user->validatePassword($this->$attribute)) {
            $this->addError($attribute, 'Неправильный пароль');
        }
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var Client $client */
        $client = Yii::$app->user->identity;

        $client->name = $this->name;
        $client->email = $this->email;

        if (!empty($this->newPassword)) {
            $client->password = $this->newPassword;
        }

        return $client->save();
    }

    public function loadCurrentData()
    {
        /** @var Client $client */
        $client = Yii::$app->user->identity;

        $this->name = $client->name;
        $this->email = $client->email;
    }
}
