<?php

use yii\db\Migration;

/**
 * Class m191019_105325_alter_client_table
 */
class m191019_105325_alter_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%client%}}', 'email', $this->string());
        $this->addColumn('{{%client%}}', 'password_hash', $this->string());
        $this->addColumn('{{%client%}}', 'auth_key', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%client%}}', 'email');
        $this->dropColumn('{{%client%}}', 'password_hash');
        $this->dropColumn('{{%client%}}', 'auth_key');
    }
}
