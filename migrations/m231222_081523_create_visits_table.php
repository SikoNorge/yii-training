<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%visits}}`.
 */
class m231222_081523_create_visits_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('visits', [
            'id' => $this->primaryKey(),
            'profile_id' => $this->integer()->notNull(),
            'user_id' => $this->integer(),
            'visit_time' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Indexe hinzufügen, falls benötigt
        $this->createIndex(
            'idx-visits-profile_id',
            'visits',
            'profile_id'
        );

        // Fremdschlüssel zu users-Tabelle hinzufügen
        $this->addForeignKey(
            'fk-visits-user_id',
            'visits',
            'user_id',
            'users', // Name der Benutzertabelle
            'id',
            'CASCADE' // Aktion bei Lösch- oder Änderungsanweisungen
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //Fremdschlüssel wird gelöscht
        $this->dropForeignKey('fk-visits-user_id', 'visits');
        //Tabelle wird gelöscht
        $this->dropTable('visits');
    }
}
