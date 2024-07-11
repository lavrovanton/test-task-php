<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%services}}`.
 */
class m240709_115550_create_services_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%services}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string()->notNull(),
            'ip' => $this->string(15)->notNull(),
            'domain' => $this->string()->notNull(),
            'client_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-services-client_id',
            'services',
            'client_id',
            false,
        );

        $this->addForeignKey(
            'fk-services-client_id',
            'services',
            'client_id',
            'clients',
            'id',
            'CASCADE',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-services-client_id',
            'services'
        );

        $this->dropIndex(
            'idx-services-client_id',
            'services'
        );

        $this->dropTable('{{%services}}');
    }
}
