<?php

class m160526_133037_create_table_steps_evidence extends EDbMigration
{
	public function up()
	{
		$this->createTable('current_step_evidence', array(
			'id' => 'pk',
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

	public function down()
	{
		$this->dropTable("current_step_evidence");
	}
}