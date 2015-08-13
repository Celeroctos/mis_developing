<?php

class m150813_074244_medical_directions_doctor_dest_id extends CDbMigration
{
	public function up()
	{
		$this->execute("ALTER TABLE hospital.medical_directions ADD COLUMN doctor_dest_id integer");
		$this->execute("COMMENT ON COLUMN hospital.medical_directions.doctor_dest_id IS 'ID назначенного врача';");		
	}

	public function down()
	{
		echo "m150813_074244_medical_directions_doctor_dest_id does not support migration down.\n";
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