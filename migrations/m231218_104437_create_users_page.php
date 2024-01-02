<?php

use yii\db\Migration;

/**
 * Class m231218_104437_create_users_page
 */
class m231218_104437_create_users_page extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('profilePage', [
            'profile_id' => $this->primaryKey(),
            'profile_about' => $this->string(64),
            'profile_text' => $this->string(64),
            'profile_title' => $this->string(120),
            'id' => $this->integer(),
            'created_at' => $this->dateTime()->defaultValue(Date('Y-m-d H:i:s')),
            'updated_at' => $this->dateTime()->defaultValue(Date('Y-m-d H:i:s')),
        ]);

        $this->addForeignKey(
            'profiles_user_id_fk',
            'profilePage',
            'id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable("profilePage");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231218_104437_create_users_page cannot be reverted.\n";

        return false;
    }
    */
}
