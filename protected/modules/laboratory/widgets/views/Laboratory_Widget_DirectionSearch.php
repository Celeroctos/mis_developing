<?php
/**
 * @var $this Laboratory_Widget_DirectionSearch
 */
?>
<h4>Поиск</h4>
<hr>
<?php $this->widget('AutoForm', [
	'id' => 'direction-search-form',
	'url' => Yii::app()->createUrl('laboratory/direction/search'),
	'model' => new Laboratory_Form_DirectionSearch(),
	'defaults' => [
		'widget' => $this->widget,
		'provider' => $this->provider,
		'config' => $this->config,
	]
]) ?>
<hr>
<div class="btn-group btn-group-justified" style="margin-bottom: 10px;">
	<a class="btn btn-success direction-search-button">
		<span class="glyphicon glyphicon-search"></span> Найти
	</a>
    <a class="btn btn-default direction-search-cancel-button">
        <span class="glyphicon glyphicon-remove"></span> Сбросить
    </a>
</div>