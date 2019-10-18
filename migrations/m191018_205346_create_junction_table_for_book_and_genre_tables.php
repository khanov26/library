<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book_genre}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%book}}`
 * - `{{%genre}}`
 */
class m191018_205346_create_junction_table_for_book_and_genre_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%book_genre}}', [
            'book_id' => $this->integer(),
            'genre_id' => $this->integer(),
            'PRIMARY KEY(book_id, genre_id)',
        ]);

        // creates index for column `book_id`
        $this->createIndex(
            '{{%idx-book_genre-book_id}}',
            '{{%book_genre}}',
            'book_id'
        );

        // add foreign key for table `{{%book}}`
        $this->addForeignKey(
            '{{%fk-book_genre-book_id}}',
            '{{%book_genre}}',
            'book_id',
            '{{%book}}',
            'id',
            'CASCADE'
        );

        // creates index for column `genre_id`
        $this->createIndex(
            '{{%idx-book_genre-genre_id}}',
            '{{%book_genre}}',
            'genre_id'
        );

        // add foreign key for table `{{%genre}}`
        $this->addForeignKey(
            '{{%fk-book_genre-genre_id}}',
            '{{%book_genre}}',
            'genre_id',
            '{{%genre}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%book}}`
        $this->dropForeignKey(
            '{{%fk-book_genre-book_id}}',
            '{{%book_genre}}'
        );

        // drops index for column `book_id`
        $this->dropIndex(
            '{{%idx-book_genre-book_id}}',
            '{{%book_genre}}'
        );

        // drops foreign key for table `{{%genre}}`
        $this->dropForeignKey(
            '{{%fk-book_genre-genre_id}}',
            '{{%book_genre}}'
        );

        // drops index for column `genre_id`
        $this->dropIndex(
            '{{%idx-book_genre-genre_id}}',
            '{{%book_genre}}'
        );

        $this->dropTable('{{%book_genre}}');
    }
}
