<?php
/**
 * @var MedcardSearch $this - Self instance
 */
?>

<div class="row">
	<div class="col-xs-12" onkeydown="if (arguments[0].keyCode == 13) { MedcardSearch.search($(this).parents('.modal:eq(0)')); }">
		<div class="col-xs-5">
			<h4>Поиск</h4>
			<br>
			<?php $this->widget("AutoForm", [
				"model" => new LMedcardSearchForm(),
				"id" => "medcard-search-form"
			]); ?>
		</div>
		<div class="col-xs-5">
			<h4>Дата проведения анализа</h4>
			<br>
			<?php $this->widget("AutoForm", [
				"model" => new LAnalysisSearchForm(),
				"id" => "medcard-range-form"
			]); ?>
		</div>
		<div class="col-xs-2"></div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<hr>
		<button id="medcard-search-button" class="btn btn-success btn-block" type="button" data-loading-text="Загрузка...">Поиск</button>
		<hr>
		<div id="medcard-search-table-wrapper">
			<?php $this->widget($this->tableWidget, [
				"emptyData" => true
			]) ?>
		</div>
	</div>
</div>
