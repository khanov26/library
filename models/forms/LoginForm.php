<?php

namespace app\models\forms;

use app\models\Client;
use Yii;
use yii\base\Model;

/**
 * Class LoginForm
 *
 * @property Client $client
 */
class LoginForm extends Model
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $password;

    /**
     * @var bool
     */
    public $rememberMe = true;

    /** @var Client */
    private $_client;


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'Ваш адрес электронной почты',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            ['email', 'trim'],
            [['email', 'password'], 'required', 'message' => 'Необходимо заполнить это поле'],

            ['email', 'email', 'message' => 'Неправильный формат адреса'],

            ['password', 'string', 'min' => 6, 'tooShort' => 'Пароль должен содержать минимум 6 символов'],
            ['password', 'validatePassword'],

            ['rememberMe', 'boolean'],
        ];
    }

    /**
     * Проверяет правильность пароля
     *
     * @param string $attribute
     * @param array $params
     */
    public function validatePassword($attribute, $params)
    {
        if ($this->hasErrors()) {
            return;
        }

        if (!$this->client || !$this->client->validatePassword($this->$attribute)) {
            $this->addError($attribute, 'Неправильный логин или пароль');
        }
    }

    /**
     * Залогинивает пользователя
     *
     * @return bool
     */
    public function login(): bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->client, $this->rememberMe ? 30 * 24 * 60 * 60 : 0);
        }

        return false;
    }

    /**
     * Возвращает пользователя по его email
     *
     * @return Client|null
     */
    public function getClient(): ?Client
    {
        if ($this->_client === null) {
            $this->_client = Client::findOne(['email' => $this->email]);
        }

        return $this->_client;
    }
}
