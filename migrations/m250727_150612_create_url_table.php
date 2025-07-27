<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%url}}`.
 */
class m250727_150612_create_url_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%url}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string(2048)->notNull(),
            'url_code' => $this->string(8)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'visit_count' => $this->integer()->defaultValue(0)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%url}}');
    }
}
