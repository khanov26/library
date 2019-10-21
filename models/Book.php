<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%book}}".
 *
 * @property int $id
 * @property string $name
 * @property int $year
 * @property int $author_id
 *
 * @property Author $author
 * @property Genre[] $genres
 * @property Borrow[] $borrows
 */
class Book extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'author_id'], 'required'],
            [['year', 'author_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::className(), 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'year' => 'Год издания',
            'author_id' => 'Автор',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGenres()
    {
        return $this->hasMany(Genre::className(), ['id' => 'genre_id'])->viaTable('{{%book_genre}}', ['book_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBorrows()
    {
        return $this->hasMany(Borrow::className(), ['book_id' => 'id']);
    }

    private function getLastBorrow()
    {
        return $this->getBorrows()->orderBy(['id' => SORT_DESC])->limit(1)->one();
    }

    public function isBorrowed(): bool
    {
        /** @var Borrow $lastBorrow */
        $lastBorrow = $this->getLastBorrow();
        return $lastBorrow !== null && $lastBorrow->brought_time === null;
    }

    public function borrow(Client $client): bool
    {
        if ($this->isBorrowed()) {
            return false;
        }

        $borrow = new Borrow([
            'book_id' => $this->id,
            'client_id' => $client->id,
            'taken_time' => time(),
            'status' => Borrow::STATUS_PENDING,
        ]);

        return $borrow->save();
    }

    public function canBeCanceled(Client $client): bool
    {
        /** @var Borrow $lastBorrow */
        $lastBorrow = $this->getLastBorrow();

        return $lastBorrow !== null
            && $lastBorrow->client_id === $client->id
            && $lastBorrow->status === Borrow::STATUS_PENDING;
    }

    public function cancelBorrow(Client $client): bool
    {
        /** @var Borrow $lastBorrow */
        $lastBorrow = $this->getLastBorrow();
        if ($this->canBeCanceled($client)) {
            return (bool) $lastBorrow->delete();
        }

        return false;
    }

    public function canBeBroughtBack(Client $client): bool
    {
        /** @var Borrow $lastBorrow */
        $lastBorrow = $this->getLastBorrow();

        return $lastBorrow !== null
            && $lastBorrow->client_id === $client->id
            && $lastBorrow->brought_time === null
            && $lastBorrow->status === Borrow::STATUS_RESOLVED;
    }

    public function bringBack(Client $client): bool
    {
        /** @var Borrow $lastBorrow */
        $lastBorrow = $this->getLastBorrow();
        if ($lastBorrow !== null
            && $lastBorrow->client_id === $client->id
            && $lastBorrow->brought_time === null
            && $lastBorrow->status === Borrow::STATUS_RESOLVED
        ) {
            $lastBorrow->brought_time = time();
            return $lastBorrow->save();
        }

        return false;
    }
}
