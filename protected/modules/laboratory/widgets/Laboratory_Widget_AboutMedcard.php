<?php

class Laboratory_Widget_AboutMedcard extends Widget {

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
			throw new CException('Medcard viewer requires medcard identification number, see [Laboratory_Widget_AboutMedcard::medcard]');
		}
		if (($medcard = Laboratory_Medcard::model()->findByPk($this->medcard)) == null) {
			throw new CException('Unresolved medcard identification number "'. $this->medcard .'"');
		}
		if (($patient = Laboratory_Patient::model()->findByPk($medcard->{'patient_id'})) == null) {
			throw new CException('Unresolved patient identification number "'. $medcard->{'patient_id'} .'"');
		}
		$age = DateTime::createFromFormat('Y-m-d', $patient->{'birthday'})
			->diff(new DateTime())->y;
		foreach ($medcard->getAttributes() as $key => $value) {
			if (empty($value)) {
				$medcard->setAttribute($key, 'Нет');
			}
		}
		if (empty($patient->{'contact'})) {
			$patient->{'contact'} = 'Нет';
		}
		if (empty($patient->{'work_place'})) {
			$patient->{'work_place'} = 'Нет';
		}
		if (empty($medcard->{'mis_medcard'})) {
			$medcard->{'mis_medcard'} = 'Нет';
		}
		if (!$address = Laboratory_Address::model()->findByPk($patient->{'address_id'})) {
			throw new CException('Unresolved address identification number "'. $patient->{'address_id'} .'"');
		}
		if (!$registerAddress = Laboratory_Address::model()->findByPk($patient->{'register_address_id'})) {
			throw new CException('Unresolved address identification number "'. $patient->{'register_address_id'} .'"');
		}
		$this->render('Laboratory_Widget_AboutMedcard', [
			'medcard' => $medcard,
			'age' => $age,
			'patient' => $patient,
			'address' => $address,
			'registerAddress' => $registerAddress
		]);
	}
}