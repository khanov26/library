<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%borrow}}`.
 */
class m191021_130826_add_status_column_to_borrow_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%borrow}}', 'status', $this->smallInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%borrow}}', 'status');
    }
}
