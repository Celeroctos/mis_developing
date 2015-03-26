<?php

class m150325_122129_doctor_greeting_time_limit extends CDbMigration
{
	public function up() {
        $this->getDbConnection()->createCommand(
            "ALTER TABLE mis.doctors ADD greeting_time_limit INT"
        )->execute();
	}

	public function down() {
         $this->getDbConnection()->createCommand(
             "ALTER TABLE mis.doctors DROP COLUMN greeting_time_limit"
         )->execute();
	}
}