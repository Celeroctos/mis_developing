<?php

class m150813_140553_medical_directions_date_dest extends CDbMigration
{
	public function up()
	{
		$this->execute("ALTER TABLE hospital.medical_directions ADD COLUMN date_dest date");
		$this->execute("COMMENT ON COLUMN hospital.medical_directions.date_dest IS 'Назначенная дата';");		
	}

	public function down()
	{
		echo "m150813_140553_medical_directions_date_dest does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}