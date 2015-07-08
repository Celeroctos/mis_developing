<?php
/**
 * @var Laboratory_Widget_MedcardSearch $this - Self instance
 */
?>
<div class="medcard-search-wrapper">
	<div class="row no-margin">
		<div class="col-xs-12 medcard-search-handler">
            <h4>Поиск</h4>
            <hr>
			<div class="col-xs-5">
				<?php $this->widget('AutoForm', [
					'model' => new Laboratory_Form_MedcardSearch(),
					'id' => 'medcard-search-form'
				]); ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<hr>
			<button id="medcard-search-button" class="btn btn-success btn-block" type="button" data-loading-text="Загрузка...">Поиск</button>
			<hr>
			<div id="medcard-search-table-wrapper">
				<?php $this->widget('GridTable', [
					'provider' => new $this->provider($this->config)
				]) ?>
			</div>
		</div>
	</div>
</div>