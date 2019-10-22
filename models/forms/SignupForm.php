<?php

namespace app\models\forms;

use app\models\Client;
use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    const SCENARIO_SIGNUP_BY_ADMIN = 'signup_by_admin';
    const SCENARIO_SIGNUP_BY_CLIENT = 'signup_by_client';

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
    public function init()
    {
        parent::init();

        if ($this->scenario === self::SCENARIO_DEFAULT) {
            $this->scenario = self::SCENARIO_SIGNUP_BY_CLIENT;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios[self::SCENARIO_SIGNUP_BY_ADMIN] = ['name', 'email', 'password'];
        $scenarios[self::SCENARIO_SIGNUP_BY_CLIENT] =  array_merge($scenarios[self::SCENARIO_SIGNUP_BY_ADMIN], ['passwordConfirm']);

        return $scenarios;
    }

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
     * @return Client|null
     * @throws \yii\base\Exception
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $client = new Client();
        $client->email = $this->email;
        $client->name = $this->name;
        $client->password = $this->password;

        if ($client->save(false)) {
            $auth = Yii::$app->authManager;
            $clientRole = $auth->getRole('client');
            $auth->assign($clientRole, $client->getId());
            return $client;
        }

        return null;
    }
}
