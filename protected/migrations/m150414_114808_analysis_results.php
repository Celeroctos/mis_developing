<?php

class m150414_114808_analysis_results extends CDbMigration
{
	/*public function up()
	{
	}

	public function down()
	{
		echo "m150414_114808_analysis_results does not support migration down.\n";
		return false;
	}*/

	
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	$connection=Yii::app()->db;
	$sql_alter_analysis=<<<HERE
	ALTER TABLE lis.analysis
   ALTER COLUMN registration_time TYPE timestamp with time zone;
HERE;

	$sql_create_analysis_resuts1=<<<HERE
	CREATE TABLE lis.analysis_results
(
  id serial NOT NULL,
  analysis_id integer,
  seq_number integer,
  testid character varying, 
  val character varying,
  units character varying,
  resultflag character(2), 
  resultstatus character(1), 
  comment character varying,
  CONSTRAINT pk_analisys_result PRIMARY KEY (id),
  CONSTRAINT analisys_analisys_result FOREIGN KEY (analysis_id)
      REFERENCES lis.analysis (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
HERE;

	$sql_create_analysis_resuts2=<<<HERE
ALTER TABLE lis.analysis_results
  OWNER TO postgres;
HERE;
	$sql_create_analysis_resuts3=<<<HERE
COMMENT ON COLUMN lis.analysis_results.testid IS 'This field consists of four
components; the first three are
not used:
Not used
^  Not used
^  Not used
^p O 2 Parameter name
^M  Parameter type
Parameter names are listed in
Appendix 1.
Possible parameter types are:
“C”
Calculated parameter
“D”
Default parameter
“E”
Estimated parameter
“I”
Input parameter
“M”
Measured parameter
“ ”
Parameter type not specified';
HERE;
	$sql_create_analysis_resuts4=<<<HERE
COMMENT ON COLUMN lis.analysis_results.resultflag IS 'Possible result flags are:
“N”
Normal value
“L”
Below low normal range
“H”
Above high normal range
“LL”
Below low critical range
“HH”
Above high critical range
“<”
Below analyzer measuring range
“>”
Above analyzer measuring range';
HERE;
	$sql_create_analysis_resuts5=<<<HERE
COMMENT ON COLUMN lis.analysis_results.resultstatus IS '“F” indicating final result.
“C” indicating corrected
parameter result if Audit Trail is
enabled.
“R” indicating a retransmitted
parameter which has not been
corrected. Only sent if Audit
Trail is enabled.';
HERE;

$command=$connection->createCommand($sql_alter_analysis);
			$command->execute();
			unset($command); // На всякий случай.   
$command=$connection->createCommand($sql_create_analysis_resuts1);
			$command->execute();
			unset($command); // На всякий случай.   
$command=$connection->createCommand($sql_create_analysis_resuts2);
			$command->execute();
			unset($command); // На всякий случай.   
$command=$connection->createCommand($sql_create_analysis_resuts3);
			$command->execute();
			unset($command); // На всякий случай.   
$command=$connection->createCommand($sql_create_analysis_resuts4);
			$command->execute();
			unset($command); // На всякий случай.   
$command=$connection->createCommand($sql_create_analysis_resuts5);
			$command->execute();
			unset($command); // На всякий случай.   
	}

	public function safeDown()
	{
	
	$connection=Yii::app()->db;
	$sql_drop_analysis_results=<<<HERE
	DROP TABLE lis.analysis_results;	
HERE;
$command=$connection->createCommand($sql_drop_analysis_results);
			$command->execute();
			unset($command); // На всякий случай.   

	}
	
}