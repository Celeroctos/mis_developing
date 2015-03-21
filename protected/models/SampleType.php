<?php

class SampleType extends GActiveRecord {

	public function getForm() {
		return new SampleTypeForm();
	}

	public function tableName() {
		return "lis.sample_type";
	}
}