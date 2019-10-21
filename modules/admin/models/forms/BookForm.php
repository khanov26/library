<?php

namespace app\modules\admin\models\forms;

use app\models\Author;
use app\models\Book;
use app\models\Genre;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

class BookForm extends Model
{
    /** @var string */
    public $name;

    /** @var int */
    public $year;

    /** @var string */
    public $author;

    /** @var string */
    public $genres;


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'year' => 'Год издания',
            'author' => 'Автор',
            'genres' => 'Жанр',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'year', 'genres', 'author'], 'trim'],

            [['name', 'year', 'author', 'genres'], 'required', 'message' => 'Необходимо заполнить поле'],

            [['name', 'author'], 'string', 'max' => 255],

            ['year', 'date', 'format' => 'yyyy', 'message' => 'Год должен быть указан в формате гггг'],
        ];
    }

    /**
     * @return Book|null
     */
    public function save(): ?Book
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $author = $this->saveAuthor();
                $genres = $this->saveGenres();

                $book = new Book();
                $book->name = $this->name;
                $book->year = $this->year;

                $this->linkModels($book, 'author', $author);
                $this->linkModels($book, 'genres', $genres);

                $transaction->commit();

                return $book;
            } catch (\Throwable $e) {
                $transaction->rollBack();

                return null;
            }
        }

        return null;
    }


    public function update(Book $book): bool
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $newAuthor = $this->saveAuthor();
                $newGenres = $this->saveGenres();

                $book->name = $this->name;
                $book->year = $this->year;
                $book->save();

                // update author
                $oldAuthor = $book->author;
                if (!$oldAuthor->equals($newAuthor)) {
                    $this->linkModels($book, 'author', $newAuthor);
                }

                // update genres
                $oldGenres = $book->genres;
                $genresToBeLinked = array_udiff($newGenres, $oldGenres, function($genre1, $genre2) {
                   return $genre1->id <=> $genre2->id;
                });
                $genresToBeUnlinked = array_udiff($oldGenres, $newGenres, function($genre1, $genre2) {
                    return $genre1->id <=> $genre2->id;
                });

                $this->linkModels($book, 'genres', $genresToBeLinked);
                $this->unlinkModels($book, 'genres', $genresToBeUnlinked);

                $transaction->commit();

                return true;
            } catch (\Throwable $e) {
                $transaction->rollBack();

                return false;
            }
        }

        return false;
    }

    public function loadData(Book $book): void
    {
        $this->name = $book->name;
        $this->year = $book->year;
        $this->author = $book->author->name;
        $this->genres = implode(', ', array_column($book->genres, 'name'));
    }

    private function saveAuthor()
    {
        $author = Author::findOne(['name' => $this->author]);

        if ($author === null) {
            $author = new Author(['name' => $this->author]);
            $author->save();
        }

        return $author;
    }

    private function saveGenres()
    {
        $genresNames = preg_split('/,\s*/', $this->genres, null, PREG_SPLIT_NO_EMPTY);

        return array_map(static function($genreName) {
            $genre = Genre::findOne(['name' => $genreName]);
            if ($genre === null) {
                $genre = new Genre(['name' => $genreName]);
                $genre->save();
            }
            return $genre;
        }, $genresNames);
    }

    private function linkModels(ActiveRecord $mainModel, string $linkName, $modelToLink)
    {
        if (is_array($modelToLink)) {
            array_walk($modelToLink, static function ($model) use ($mainModel, $linkName) {
                $mainModel->link($linkName, $model);
            });
        } else {
            $mainModel->link($linkName, $modelToLink);
        }
    }

    private function unlinkModels(ActiveRecord $mainModel, string $linkName, $modelToLink)
    {
        if (is_array($modelToLink)) {
            array_walk($modelToLink, static function ($model) use ($mainModel, $linkName) {
                $mainModel->unlink($linkName, $model, true);
            });
        } else {
            $mainModel->unlink($linkName, $modelToLink, true);
        }
    }
}
