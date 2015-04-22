<?php

class MedcardViewer extends Widget {

	/**
	 * @var int - Medcard identification number (primary key) from
	 *	database's table (lis/medcard/id)
	 */
	public $medcard;

	/**
	 * Run widget
	 * @throws CException
	 */
	public function run() {
		if (empty($this->medcard)) {
			throw new CException("Medcard viewer requires medcard identification number, see [MedcardViewer::medcard]");
		}
		if (($medcard = LMedcard::model()->findByPk($this->medcard)) == null) {
			throw new CException("Unresolved medcard identification number \"{$this->medcard}\"");
		}
		if (($patient = LPatient::model()->findByPk($medcard->{"patient_id"})) == null) {
			throw new CException("Unresolved patient identification number \"{$medcard->{"patient_id"}}\"");
		}
		$age = DateTime::createFromFormat("Y-m-d", $patient->{"birthday"})
			->diff(new DateTime())->y;
		foreach ($medcard->getAttributes() as $key => $value) {
			if (empty($value)) {
				$medcard->setAttribute($key, "Нет");
			}
		}
		if ($address = LAddress::model()->findByPk($patient->{"address_id"}) == null) {
			throw new CException("Unresolved address identification number \"{$this->{"address_id"}}\"");
		}
		if ($registerAddress = LAddress::model()->findByPk($patient->{"register_address_id"}) == null) {
			throw new CException("Unresolved address identification number \"{$this->{"register_address_id"}}\"");
		}
		$this->render(__CLASS__, [
			"medcard" => $medcard,
			"age" => $age,
			"patient" => $patient,
			"address" => $address,
			"registerAddress" => $registerAddress
		]);
	}
}