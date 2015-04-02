<?php

class m150330_093741_hospital_30032015 extends CDbMigration {
	public function up() {
        $this->getDbConnection()->createCommand(
            "INSERT INTO mis.access_actions_groups (id, name) VALUES(9, 'Стационар')"
        )->execute();

        $this->getDbConnection()->createCommand(
            "INSERT INTO mis.access_actions (\"name\", \"group\", \"accessKey\") VALUES('Видимость модуля в меню', 9, 'hospitalMenu')"
        )->execute();
	}

	public function down() {
        $this->getDbConnection()->createCommand(
            "DELETE FROM mis.access_action_groups WHERE id = 9"
        )->execute();
	}
}