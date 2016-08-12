<?php

use yii\db\Migration;

class m160526_133040_create_save_step_evidence extends Migration
{
	public function up()
	{
		if(!\Yii::$app->db->schema->getTableSchema("save_steps_evidence")) {
			$this->createTable('save_steps_evidence', array(
				'id' => 'pk',
				'name' => 'varchar(100) NOT NULL',
				'step1' => 'text NULL',
				'step2' => 'text NULL',
				'step3' => 'text NULL',
				'obj_step1' => 'text NULL',
				'obj_step2' => 'text NULL',
				'obj_step3' => 'text NULL',
				'created_at' => 'datetime NOT NULL',
				'created_by' => 'int(11) NOT NULL',
				'updated_at' => 'datetime NOT NULL',
				'updated_by' => 'int(11) NOT NULL',
			), '');
		}
	}

	public function down()
	{
		$this->dropTable("save_steps_evidence");
	}
}