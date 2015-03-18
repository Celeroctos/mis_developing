<?php

class LMedcard2 extends LModel {

	public $privilege_code;
	public $snils;
	public $address;
	public $address_reg;
	public $doctype;
	public $serie;
	public $docnumber;
	public $gived_date;
	public $contact;
	public $invalid_group;
	public $card_number;
	public $enterprise_id;
	public $policy_id;
	public $reg_date;
	public $work_place;
	public $work_address;
	public $post;
	public $profession;
	public $motion;
	public $address_str;
	public $address_reg_str;
	public $user_created;

	/**
	 * @param string $className - Name of class to load
	 * @return LMedcard2 - Model instance
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * Find information about patient by it's card number
	 * @param string $number - Number of patients card (mis.medcards.card_number)
	 * @return mixed - Mixed object with found row
	 * @throws CDbException
	 */
	public function fetchByNumber($number) {
		return $this->getDbConnection()->createCommand()
			->select("*")
			->from("mis.medcards as m")
			->join("mis.oms as o", "m.policy_id = o.id")
			->where("m.card_number = :card_number")
			->queryRow(true, [
				":card_number" => $number
			]);
	}

	/**
	 * Fetch list with patients and it's oms information
	 * @return array - Array with patients
	 * @throws CDbException
	 */
	public function fetchListWithPatients() {
		return $this->getDbConnection()->createCommand()
			->select("m.card_number as number, m.contact as phone, o.first_name as name, o.middle_name as surname, o.last_name as patronymic")
			->from("mis.medcards as m")
			->join("mis.oms as o", "m.policy_id = o.id")
			->leftJoin("lis.analysis as a", "a.medcard_number = m.card_number")
			->queryAll();
	}

	/**
	 * Fetch full information about medcard with patient info and others
	 * @param string $number - Number of medcard to find
	 * @return mixed - Found rows
	 * @throws CDbException
	 * @throws CException
	 */
	public function fetchInformation($number) {
		$row = $this->getDbConnection()->createCommand()
			->select("*")
			->from("mis.medcards as m")
			->join("mis.oms as o", "m.policy_id = o.id")
			->where("m.card_number = :number", [
				":number" => $number
			])->queryRow();
		if (!$row) {
			return null;
		}
		$row["address"] = $this->getAddressInfo(
			json_decode($row["address"], true)
		);
		$row["address_reg"] = $this->getAddressInfo(
			json_decode($row["address_reg"], true)
		);
		return $row;
	}

	/**
	 * Fetch information from mis medcard and format it to laboratory
	 * medcard with all sub forms
	 * @param string $number - Card number in mis
	 * @return array - Array with model information
	 */
	public function fetchInformationLaboratoryLike($number) {
		if (($model = $this->fetchInformation($number)) == null) {
			return null;
		}
		$result = [
			"medcard" => [
				"mis_medcard" => $this->read($model, "card_number"),
				"enterprise_id" => $this->read($model, "enterprise_id"),
				"card_number" => null,
				"sender_id" => null,
				"patient_id" => null
			],
			"patient" => [
				"surname" => $this->read($model, "last_name"),
				"name" => $this->read($model, "first_name"),
				"patronymic" => $this->read($model, "middle_name"),
				"sex" => $this->read($model, "gender"),
				"birthday" => $this->read($model, "birthday"),
				"policy_number" => $this->read($model, "oms_number"),
				"policy_issue_date" => $this->read($model, "givedate"),
				"policy_insurance_id" => $this->read($model, "insurance"),
				"register_address_id" => null,
				"address_id" => null,
				"document_type" => $this->read($model, "doctype")
			]
		];
		if (($address = $this->read($model, "address")) != null) {
			$result["address_id"] = [
				"street_name" => $this->read($address, "street", "name"),
				"house_number" => $this->read($address, "house"),
				"flat_number" => $this->read($address, "flat"),
				"post_index" => $this->read($address, "postindex"),
				"region_name" => $this->read($address, "region", "name"),
				"district_name" => $this->read($address, "district", "name")
			];
		}
		if (($address = $this->read($model, "address_reg")) != null) {
			$result["register_address_id"] = [
				"street_name" => $this->read($address, "street", "name"),
				"house_number" => $this->read($address, "house"),
				"flat_number" => $this->read($address, "flat"),
				"post_index" => $this->read($address, "postindex"),
				"region_name" => $this->read($address, "region", "name"),
				"district_name" => $this->read($address, "district", "name")
			];
		}
		return $result;
	}

	/**
	 * Ready key sequence from object or array
	 * @param mixed $obj - Mixed object or array
	 * @param string... [$key] - Sequence with keys
	 * @return mixed|null - Found value or null
	 */
	private function read($obj) {
		for ($i = 1; $i < func_num_args(); $i++) {
			$key = func_get_arg($i);
			if (is_array($obj)) {
				$obj = isset($obj[$key]) ? $obj[$key] : null;
			} else {
				$obj = isset($obj->$key) ? $obj->$key : null;
			}
			if ($obj == null) {
				break;
			}
		}
		return $obj;
	}

	/**
	 * Fetch information about address from cladr
	 * @param array $row - Array with parsed json object from [mis.medcards.address]
	 * @return array - Array with received information about address
	 * @throws CDbException - Database exceptions
	 * @throws CException - If some identification number broken
	 */
	protected function getAddressInfo($row) {
		$address = [
			"region" => null,
			"district" => null,
			"street" => null,
			"house" => null,
			"flag" => null,
			"building" => null,
			"post" => null
		];
		if (isset($row["regionId"])) {
			$region = $this->getDbConnection()->createCommand()
				->select("*")
				->from("mis.cladr_regions")
				->where("id = :id", [
					":id" => $row["regionId"]
				])
				->queryRow();
			if (!$region) {
				throw new CException("Unresolved region identification number \"{$row["regionId"]}\"");
			}
			$address["region"] = $region;
		}
		if (isset($row["districtId"])) {
			$district = $this->getDbConnection()->createCommand()
				->select("*")
				->from("mis.cladr_districts")
				->where("id = :id", [
					":id" => $row["districtId"]
				])
				->queryRow();
			if (!$district) {
				throw new CException("Unresolved region identification number \"{$row["districtId"]}\"");
			}
			$address["district"] = $district;
		}
		if (isset($row["streetId"])) {
			$street = $this->getDbConnection()->createCommand()
				->select("*")
				->from("mis.cladr_streets")
				->where("id = :id", [
					":id" => $row["streetId"]
				])
				->queryRow();
			if (!$street) {
				throw new CException("Unresolved street identification number \"{$row["streetId"]}\"");
			}
			$address["street"] = $street;
		}
		return $address + $row;
	}

	/**
	 * Override that method to return count of rows in table
	 * @param CDbCriteria $criteria - Search criteria
	 * @return int - Count of rows in current table
	 * @throws CDbException
	 */
	public function getTableCount(CDbCriteria $criteria = null) {
		$query = $this->getDbConnection()->createCommand()
			->select("count(1) as count")
			->from("mis.medcards as m")
			->join("mis.oms as o", "m.policy_id = o.id")
			->leftJoin("lis.analysis as a", "a.medcard_number = m.card_number");
		if ($criteria != null && $criteria instanceof CDbCriteria) {
			$query->andWhere($criteria->condition, $criteria->params);
		}
		return $query->queryRow()["count"];
	}

	/**
	 * Override that method to return command for table widget
	 * @return CDbCommand - Command with selection query
	 * @throws CDbException
	 */
	public function getTable() {
		return $this->getDbConnection()->createCommand()
			->select("
                m.card_number as number,
                m.contact as phone,
                o.last_name || ' ' || o.first_name || ' ' || o.middle_name as fio,
                o.birthday as birthday,
                e.shortname as enterprise,
                cast(a.registration_date as date) as registration_date")
			->from("mis.medcards as m")
			->join("mis.oms as o", "m.policy_id = o.id")
			->leftJoin("lis.analysis as a", "a.medcard_number = m.card_number")
			->leftJoin("mis.enterprise_params as e", "e.id = m.enterprise_id");
	}

	/**
	 * @return string - Name of table
	 */
	public function tableName() {
		return "mis.medcards";
	}
}