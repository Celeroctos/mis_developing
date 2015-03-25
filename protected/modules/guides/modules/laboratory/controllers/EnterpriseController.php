<?php

class EnterpriseController extends GController {

	public function getClefTable() {
		return [
			"table" => "lis.enterprise_clef",
			"key" => "enterprise_id"
		];
	}

	public function getModel() {
		return null;
	}
}