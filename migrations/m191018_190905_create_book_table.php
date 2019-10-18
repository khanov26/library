<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book}}`.
 */
class m191018_190905_create_book_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%book}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'year' => $this->integer(4),
            'author_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-book-author_id',
            '{{%book}}',
            'author_id'
        );

        $this->addForeignKey(
            'fk-book-author_id',
            '{{%book}}',
            'author_id',
            '{{%author}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-post-author_id', '{{%book}}');

        $this->dropIndex('idx-book-author_id', '{{%book}}');

        $this->dropTable('{{%book}}');
    }
}
