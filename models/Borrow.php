<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%borrow}}".
 *
 * @property int $id
 * @property int $book_id
 * @property int $client_id
 * @property int $taken_time
 * @property int $brought_time
 *
 * @property Book $book
 * @property Client $client
 */
class Borrow extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%borrow}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_id', 'client_id', 'taken_time'], 'required'],
            [['book_id', 'client_id', 'taken_time', 'brought_time'], 'integer'],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Book::className(), 'targetAttribute' => ['book_id' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book_id' => 'Книга',
            'client_id' => 'Клиент',
            'taken_time' => 'Время выдачи',
            'brought_time' => 'Время возврата',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Book::className(), ['id' => 'book_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }
}
