<?php

class m150330_093741_hospital_30032015 extends CDbMigration {

	public function safeUp() {
		$this->execute("INSERT INTO mis.access_actions_groups (id, name) VALUES(9, 'Стационар')");
		$this->execute("INSERT INTO mis.access_actions (\"name\", \"group\", \"accessKey\") VALUES('Видимость модуля в меню', 9, 'hospitalMenu')");
	}

	public function safeDown() {
		$this->execute("DELETE FROM mis.access_actions_groups WHERE id = 9");
	}
}