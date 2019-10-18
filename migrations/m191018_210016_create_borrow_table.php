<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%borrow}}`.
 */
class m191018_210016_create_borrow_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%borrow}}', [
            'id' => $this->primaryKey(),
            'book_id' => $this->integer()->notNull(),
            'client_id' => $this->integer()->notNull(),
            'taken_time' => $this->integer()->notNull(),
            'brought_time' => $this->integer(),
        ]);

        $this->createIndex(
            'idx-borrow-book_id',
            '{{%borrow}}',
            'book_id'
        );

        $this->addForeignKey(
            'fk-borrow-book_id',
            '{{%borrow}}',
            'book_id',
            '{{%book}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-borrow-client_id',
            '{{%borrow}}',
            'client_id'
        );

        $this->addForeignKey(
            'fk-borrow-client_id',
            '{{%borrow}}',
            'client_id',
            '{{%client}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-borrow-client_id', '{{%borrow}}');
        $this->dropIndex('idx-borrow-client_id', '{{%borrow}}');

        $this->dropForeignKey('fk-borrow-book_id', '{{%borrow}}');
        $this->dropIndex('idx-borrow-book_id', '{{%borrow}}');

        $this->dropTable('{{%borrow}}');
    }
}
