<?php

class m150601_212006_laboratory_astm_params extends CDbMigration
{
	public function safeUp()
	{
		$sql = <<< SQL

INSERT INTO lis.analysis_type_parameter (short_name, name) VALUES
  ('pH^M', 'pH^M'),
  ('pH(T)^C', 'pH(T)^C'),
  ('p50(act),T^E', 'p50(act),T^E'),
  ('p50(act)^E', 'p50(act)^E'),
  ('pCO2^M', 'pCO2^M'),
  ('pCO2(T)^C', 'pCO2(T)^C'),
  ('pO2^M', 'pO2^M'),
  ('pO2(T)^C', 'pO2(T)^C'),
  ('SBE^C', 'SBE^C'),
  ('ABE^C', 'ABE^C'),
  ('Ca++^M', 'Ca++^M'),
  ('Ca(7.4)^C', 'Ca(7.4)^C'),
  ('Cl-^M', 'Cl-^M'),
  ('Glu^M', 'Glu^M'),
  ('cH+^C', 'cH+^C'),
  ('HCO3-^C', 'HCO3-^C'),
  ('SBC^C', 'SBC^C'),
  ('K+^M', 'K+^M'),
  ('cH+(T)^C', 'cH+(T)^C'),
  ('Lac^M', 'Lac^M'),
  ('Na+^M', 'Na+^M'),
  ('tCO2(B)^C', 'tCO2(B)^C'),
  ('tHb^M', 'tHb^M'),
  ('sO2^M', 'sO2^M'),
  ('COHb^M', 'COHb^M'),
  ('RHb^M', 'RHb^M'),
  ('MetHb^M', 'MetHb^M'),
  ('T^I', 'T^I'),
  ('FIO2^I', 'FIO2^I')

SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown()
	{
		$sql = <<< SQL
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}
}