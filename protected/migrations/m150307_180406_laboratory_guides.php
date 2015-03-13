<?php

class m150307_180406_laboratory_guides extends CDbMigration
{
	public function safeUp() {
        $this->dropColumn('lis.analysis_types','automatic');
        $this->dropColumn('lis.analysis_types','manual');
        $this->addColumn('lis.analysis_types','metodics', "int not null default 0");
        $this->addColumn('lis.analysis_type_templates','seq_number', "int");
	}

	public function safeDown() {
        $this->dropColumn('lis.analysis_type_templates','seq_number');
        $this->dropColumn('lis.analysis_types','metodics');
        $this->addColumn('lis.analysis_types','automatic', "int not null default 0");
        $this->addColumn('lis.analysis_types','manual', "int not null default 0");
	}
}