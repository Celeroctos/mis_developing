<?php

class m150519_030859_laboratory_data_cleanup extends CDbMigration
{
	public function safeUp()
	{
		$sql = <<< SQL

		TRUNCATE TABLE lis.address CASCADE;
		TRUNCATE TABLE lis.analysis CASCADE;
		TRUNCATE TABLE lis.analysis_results CASCADE;
		TRUNCATE TABLE lis.analysis_type CASCADE;
		TRUNCATE TABLE lis.analysis_type_parameter CASCADE;
		TRUNCATE TABLE lis.analysis_type_to_analysis_type_parameter CASCADE;
		TRUNCATE TABLE lis.analysis_type_to_sample_type CASCADE;
		TRUNCATE TABLE lis.analyzer CASCADE;
		TRUNCATE TABLE lis.analyzer_type CASCADE;
		TRUNCATE TABLE lis.analyzer_type_to_analysis_type CASCADE;
		TRUNCATE TABLE lis.direction CASCADE;
		TRUNCATE TABLE lis.direction_parameter CASCADE;
		TRUNCATE TABLE lis.doctor_clef CASCADE;
		TRUNCATE TABLE lis.enterprise_clef CASCADE;
		TRUNCATE TABLE lis.guide CASCADE;
		TRUNCATE TABLE lis.guide_column CASCADE;
		TRUNCATE TABLE lis.guide_row CASCADE;
		TRUNCATE TABLE lis.guide_value CASCADE;
		TRUNCATE TABLE lis.medcard CASCADE;
		TRUNCATE TABLE lis.passport CASCADE;
		TRUNCATE TABLE lis.patient CASCADE;
		TRUNCATE TABLE lis.patient_category CASCADE;
		TRUNCATE TABLE lis.policy CASCADE;
		TRUNCATE TABLE lis.sample_type CASCADE
SQL;
		foreach (explode(";", $sql) as $s) {
			$this->execute($s);
		}
	}

	public function safeDown() {
	}
}