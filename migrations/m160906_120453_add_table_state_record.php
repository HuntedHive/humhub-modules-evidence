<?php

use yii\db\Migration;

class m160906_120453_add_table_state_record extends Migration
{
    public function up()
    {
        if(!\Yii::$app->db->schema->getTableSchema("state_record")) {
            $this->createTable('state_record', array(
                'id' => 'pk',
                'key' => 'varchar(255) NOT NULL',
                'created_at' => 'int(11) NOT NULL',
                'created_by' => 'int(11) NOT NULL',
            ), '');
        }
    }

    public function down()
    {
        $this->dropTable("state_record");
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
