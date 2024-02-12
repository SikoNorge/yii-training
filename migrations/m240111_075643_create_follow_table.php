<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%follow}}`.
 */
class m240111_075643_create_follow_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('follow_table', [
            'id' => $this->primaryKey(),
            'follower_id' => $this->integer()->notNull(),
            'following_id' => $this->integer()->notNull(),
            'status' => $this->string(50),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex('unique_follow', 'follow_table', ['follower_id', 'following_id'], true);

        $this->addForeignKey('fk_follow_follower', 'follow_table', 'follower_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_follow_following', 'follow_table', 'following_id', 'profile_id', 'profile_id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_follow_follower', 'follow_table');
        $this->dropForeignKey('fk_follow_following', 'follow_table');
        $this->dropIndex('unique_follow', 'follow_table');
        $this->dropTable('follow_table');
    }
}
