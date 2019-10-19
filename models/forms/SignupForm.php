<?php

namespace app\models\forms;

use app\models\Client;
use yii\base\Model;

class SignupForm extends Model
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $passwordConfirm;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email'], 'trim'],
            [['name', 'email', 'password', 'passwordConfirm'], 'required', 'message' => 'Необходимо заполнить это поле'],

            ['name', 'string', 'min' => 2, 'max' => 255],

            ['email', 'email', 'message' => 'Неправильный формат адреса'],
            ['email', 'unique', 'targetClass' => Client::class, 'message' => 'Пользователь с таким адресом уже существует'],

            ['password', 'string', 'min' => 6, 'tooShort' => 'Пароль должен содержать минимум 6 символов'],
            ['passwordConfirm', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'Ваш адрес электронной почты',
            'password' => 'Пароль',
            'passwordConfirm' => 'Повторите ваш пароль',
        ];
    }

    /**
     * Добавляет пользователя в БД
     *
     * @return bool
     * @throws \yii\base\Exception
     */
    public function signup()
    {
        if (!$this->validate()) {
            return false;
        }

        $client = new Client();
        $client->email = $this->email;
        $client->name = $this->name;
        $client->password = $this->password;

        return $client->save(false);
    }
}
