<?php

class m150805_115406_by_enterprise extends CDbMigration
{
	public function up() {
        $this->execute("ALTER TABLE mis.medcards ADD COLUMN enterprise_id integer; COMMENT ON COLUMN mis.medcards.enterprise_id IS 'ID заведения';");
        $this->execute("UPDATE mis.medcards SET enterprise_id = 1");
	}

	public function down()
	{
		echo "m150805_115406_by_enterprise does not support migration down.\n";
		return false;
	}
}