<script type="text/javascript" src="<?= Yii::app()->getBaseUrl() ?>\js\chooser.js"></script>
<script type="text/javascript" src="<?= Yii::app()->getBaseUrl() ?>\js\reception\searchAddPatient.js"></script>
<?php

/**
 * @var $this MedcardController
 */

$this->widget("Modal", [
	"title" => "Редактирование данных медкарты пациента",
	"body" => $this->getWidget("MedcardEditor"),
	"buttons" => [
		"save" => [
			"text" => "Сохранить",
			"class" => "btn btn-primary",
			"type" => "submit"
		]
	],
	"id" => "patient-medcard-edit-modal"
]);

$this->widget("MedcardSearch", [
	"mode" => "mis"
]);

?>
<hr>
<div class="btn-group" role="group">
	<a id="medcard-register-button" class="btn btn-success" href="<?= Yii::app()->getBaseUrl() . "/reception/patient/viewadd" ?>">
		Создать ЛКП
	</a>
	<button id="medcard-edit-button" class="btn btn-default disabled" data-loading-text="Загрузка...">
		Редактировать ЛКП
	</button>
    <button id="medcard-show-button" class="btn btn-default disabled" data-loading-text="Загрузка...">
        Открыть ЛКП
    </button>
</div>