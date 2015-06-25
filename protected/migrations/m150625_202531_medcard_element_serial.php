<?php

class m150625_202531_medcard_element_serial extends CDbMigration
{
	public function safeUp() {
        $this->execute('ALTER TABLE mis.medcard_elements_patient ADD pk SERIAL');
	}

	public function safeDown() {
        $this->execute('ALTER TABLE mis.medcard_elements_patient DROP pk');
	}
}