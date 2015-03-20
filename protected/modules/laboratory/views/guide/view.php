<?

/**
 * @var $this LController
 */

$this->widget("ConfirmDelete", [
    "title" => "Удалить?",
    "id" => "confirm-delete-modal"
]);

$this->widget("Modal", [
    "body" => $this->getWidget("AutoForm", [
        "url" => Yii::app()->getBaseUrl()."/laboratory/guide/register",
        "model" => new LGuideForm("register"),
        "id" => "guide-register-form"
    ]),
    "title" => "Добавление справочника",
    "id" => "guide-register-modal",
    "buttons" => [
        "register" => [
            "class" => "btn btn-primary",
            "type" => "submit",
            "text" => "Добавить"
        ]
    ]
]);

$this->widget("Modal", [
    "title" => "Редактирование значений",
    "id" => "guide-edit-values-modal",
    "buttons" => [
        "register" => [
            "class" => "btn btn-primary",
            "type" => "submit",
            "text" => "Сохранить"
        ]
    ],
    "class" => "modal-lg"
]);

?>

<div class="col-xs-12">
    <div class="col-xs-4">
        <? $this->beginWidget("Panel", [ "title" => "Справочники", "id" => "guide-panel" ]); $this->widget("GuideTable"); ?>
        <hr>
        <button data-toggle="modal" data-target="#guide-register-modal" type="button" class="btn btn-primary btn-sm">
            Добавить справочник
        </button>
        <? $this->endWidget(); ?>
    </div>
    <div class="col-xs-8">
        <div class="panel panel-default" id="guide-edit-panel">
            <div class="panel-heading" style="font-size: 15px">
                <b>Редактирование справочника</b>
            </div>
            <div class="panel-body">
                <div class="panel-content">
                    <h4 style="text-align: center">Не выбран справочник</h4>
                </div>
                <div id="guide-panel-button-group" class="hidden">
                    <button type="submit" id="panel-update" class="btn btn-primary">Сохранить</button>
                    <button type="submit" id="panel-cancel" class="btn btn-default">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
</div>