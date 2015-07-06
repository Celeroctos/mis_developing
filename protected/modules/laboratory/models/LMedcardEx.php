<?php

class LMedcardEx extends ActiveRecord {

	public function relations() {
		return [
			"policy" => [ self::BELONGS_TO, "Oms", "policy_id", "joinType" => "INNER JOIN" ],
			"enterprise" => [ self::BELONGS_TO, "Enterprise", "enterprise_id", "joinType" => "INNER JOIN" ]
		];
	}

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

	public function fetchListWithPatients() {
		return $this->getDbConnection()->createCommand()
			->select("m.card_number as number, m.contact as phone, o.first_name as name, o.middle_name as surname, o.last_name as patronymic")
			->from("mis.medcards as m")
			->join("mis.oms as o", "m.policy_id = o.id")
			->leftJoin("lis.analysis as a", "a.medcard_number = m.card_number")
			->queryAll();
	}

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

	public function fetchInformationLaboratoryLike($number) {
		if (($model = $this->fetchInformation($number)) == null) {
			return null;
		}
		$result = [
			"medcard_id" => [
				"mis_medcard" => $this->read($model, "card_number"),
				"enterprise_id" => $this->read($model, "enterprise_id"),
				"card_number" => null,
				"sender_id" => null,
				"patient_id" => null
			],
			"patient_id" => [
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
				"document_type" => $this->read($model, "doctype"),
				"contacts" => $this->read($model, "contact"),
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

	public function tableName() {
		return "mis.medcards";
	}
}