<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%url_log}}`.
 */
class m250727_150700_create_url_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%url_log}}', [
            'id' => $this->primaryKey(),
            'url_id' => $this->integer()->notNull(),
            'ip_address' => $this->string(45)->notNull(),
            'visited_at' => $this->timestamp()
        ]);
        
        $this->addForeignKey('fk_url_log_url_id', '{{%url_log}}', 'url_id', '{{%url}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_url_log_url_id', '{{%url_log}}');
        $this->dropTable('{{%url_log}}');
    }
}
