<?php
/**
 * @var $this AboutDirection - Instance of widget associated with that view file
 * @var $direction LDirection - Row with information about direction
 * @var $analysis LAnalysisType - Row with information about type of direction's analysis
 * @var $parameters array - Array with analysis type parameters
 * @var $samples array - Array with analysis type sample types
 */
?>

<?= CHtml::openTag("div", [
	"class" => "about-direction"
]) ?>
<?= CHtml::hiddenField("direction_id", $direction->id, [
	"id" => "treatment-about-direction-id"
]); ?>
<?= CHtml::hiddenField("medcard_id", $direction->medcard_id, [
	"id" => "treatment-about-direction-medcard-id"
]); ?>
<?php $this->beginWidget("Panel", [
	"title" => "Информация об образце",
	"id" => "treatment-about-direction-analysis-panel",
	"collapsible" => true
]) ?>
<div class="direction-info-wrapper" style="font-size: 15px">
	<div class="col-xs-12 no-padding">
		<div class="row no-padding">
			<div class="col-xs-4 text-right">
				<label><b>Требуемый анализ: </b></label>
			</div>
			<div class="col-xs-8 text-left">
				<span><?= trim($analysis->{"name"})." (".trim($analysis->{"short_name"}).")" ?></span>
			</div>
		</div>
		<div class="row no-padding">
			<div class="col-xs-4 text-right">
				<label><b>Тип образца: </b></label>
			</div>
			<div class="col-xs-8 text-left sample-type-list-wrapper">
				<?= CHtml::dropDownList("sampleTypes", "-1", CHtml::listData($samples, "id", "path"), [
					"class" => "form-control sample-type-list"
				]) ?>
			</div>
		</div>
		<div class="row no-padding">
			<div class="col-xs-4 text-right">
				<label><b>Параметры анализа: </b></label>
			</div>
			<div class="col-xs-8 text-left">
			<?php $this->widget("AnalysisParameterChecker", [
				"parameters" => $parameters
			]) ?>
			</div>
		</div>
		<div class="row no-padding">
			<div class="col-xs-4 text-right">
				<label><b>Дата взятия образца: </b></label>
			</div>
			<div class="col-xs-8 text-left">
				<div class="input-group">
					<input style="margin-top: 0" data-provide="datepicker" data-date-language="ru-RU" data-date-format="yyyy-mm-dd" class="form-control" value="<?= substr($direction->{"sending_date"}, 0, strpos($direction->{"sending_date"}, " ")) ?>" title="" aria-describedby="basic-addon">
					<span class="input-group-addon" id="basic-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
			</div>
		</div>
		<div class="row no-padding">
			<div class="col-xs-4 text-right">
				<label><b>Коментарий: </b></label>
			</div>
			<div class="col-xs-8 text-left">
				<textarea class="form-control" rows="6" style="resize: vertical" title=""><?= $direction->{"comment"} ?></textarea>
			</div>
		</div>
	</div>
</div>
<hr>
<div class="col-xs-12 text-center">
	<button id="send-to-laboratory-button" class="btn btn-default">
		<span class="glyphicon glyphicon-sort"></span> Передать в лабораторию
	</button>
	<button id="print-barcode-button" class="btn btn-primary">
		<span class="glyphicon glyphicon-print"></span> Печать штрих-кода
	</button>
</div>
<?php $this->endWidget("Panel") ?>
<hr>
<?php $this->widget("AboutMedcard", [
	"medcard" => $direction->medcard_id
]) ?>
<?= CHtml::closeTag("div") ?>