<?php

namespace app\models\forms;

use app\models\Client;
use Yii;
use yii\base\Model;

/**
 * Class EditForm
 *
 * @property Client $client
 */
class EditForm extends Model
{
    const SCENARIO_EDIT_ADMIN = 'edit_by_admin';
    const SCENARIO_EDIT_CLIENT = 'edit_by_client';

    public $name;
    public $email;
    public $currentPassword;
    public $newPassword;
    public $newPasswordConfirm;

    /** @var Client */
    private $_client;


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if ($this->scenario === self::SCENARIO_DEFAULT) {
            $this->scenario = self::SCENARIO_EDIT_CLIENT;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios[self::SCENARIO_EDIT_ADMIN] = ['name', 'email', 'newPassword'];
        $scenarios[self::SCENARIO_EDIT_CLIENT] = array_merge($scenarios[self::SCENARIO_EDIT_ADMIN], ['currentPassword', 'newPasswordConfirm']);

        return $scenarios;
    }

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
                return $this->client->$attribute !== $model->$attribute;
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

        $this->client->name = $this->name;
        $this->client->email = $this->email;

        if (!empty($this->newPassword)) {
            $this->client->password = $this->newPassword;
        }

        return $this->client->save();
    }

    public function loadCurrentData()
    {
        $this->name = $this->client->name;
        $this->email = $this->client->email;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        if ($this->_client === null) {
            $this->_client = Yii::$app->user->identity;
        }

        return $this->_client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client): void
    {
        $this->_client = $client;
    }
}
