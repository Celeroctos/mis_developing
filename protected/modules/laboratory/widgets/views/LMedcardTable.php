<?php
/**
 * @var LMedcardTable $this - Self instance
 */

$this->widget("LTable", [
	"widget" => $this,
    "table" => new LMedcard(),
    "header" => [
        "number" => [
            "label" => "Номер ЛКП",
			"style" => "width: 15%"
        ],
        "fio" => [
            "label" => "ФИО пациента",
			"style" => "width: 25%"
        ],
        "enterprise" => [
            "label" => "МУ направитель",
			"style" => "width: 20%"
        ],
        "birthday" => [
            "label" => "Дата рождения",
			"style" => "width: 15%"
        ],
        "phone" => [
            "label" => "Контактный телефон"
        ]
    ],
    "pk" => "number",
	"sort" => "number",
	"id" => "medcard-table",
	"limit" => 10,
	"click" => "MedcardSearch.click"
]);