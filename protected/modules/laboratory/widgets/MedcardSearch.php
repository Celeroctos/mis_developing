<?php

class MedcardSearch extends Widget {

	/**
	 * @var string - Medcard table widget, like
	 * 	MedcardTable or MedcardTable2
	 * @see MedcardTable, MedcardTable2
	 */
	public $tableWidget = "MedcardTable";

	/**
	 * Run widget to return just rendered content
	 * @throws CException
	 */
    public function run() {
        return $this->render(__CLASS__);
    }
} 