<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%post}}`.
 */
class m240104_054556_create_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('posts', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'content' => $this->text()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey(
            'fk-posts-user_id', // FK name
            'posts', // Target table name
            'user_id', // Column in current table
            'users', // Target table's column
            'id', // Target column
            'CASCADE' // Optional: ON DELETE action
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('posts');
    }
}
