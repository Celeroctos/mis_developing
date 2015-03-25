<?php

class m150321_064551_laboratory_card_generator_rule extends CDbMigration
{
	public function safeUp() {
		$sql = <<< SQL
			INSERT INTO mis.medcards_rules (prefix_id, postfix_id, value, parent_id, name, participle_mode_prefix, participle_mode_postfix, prefix_separator_id, postfix_separator_id, type) VALUES (
			  -2, 1, 0, NULL, 'Лаборатория', NULL, NULL, 4, 5, 1
			)
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown() {
		$sql = <<< SQL
			DELETE FROM mis.medcards_rules WHERE name = 'Лаборатория'
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}