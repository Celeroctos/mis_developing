<?php
/**
 * @var $this LaboratoryController
 * @var $analyzers array
 */
$this->widget('Laboratory_Modal_AboutDirection');
$this->widget('Laboratory_Modal_AnalysisResult');
$this->widget('Laboratory_Modal_QueueGuide');
?>
<script type="text/javascript" src="<?= Yii::app()->createUrl('js/laboratory/laboratory.js') ?>"></script>
<div class="treatment-header-wrapper row">
	<div class="treatment-header">
		<div class="col-xs-6 no-padding">
			<button class="btn btn-default btn-block treatment-header-rounded treatment-header-wrapper-active" data-tab="#laboratory-direction-grid-wrapper" type="button">
				<span>Направления и Анализаторы</span>
			</button>
		</div>
		<div class="col-xs-6 no-padding treatment-center-block">
			<button class="btn btn-default btn-block treatment-header-rounded" data-tab="#laboratory-ready-grid-wrapper" type="button">
				<span>Готовые результаты</span>
				<span class="badge" id="laboratory-ready-counts"><?= Laboratory_Direction::model()->getCountOf(Laboratory_Direction::STATUS_READY) ?></span>
			</button>
		</div>
	</div>
	<div class="laboratory-table-wrapper table-wrapper">
		<div id="laboratory-direction-grid-wrapper" class="col-xs-12 no-padding">
			<?php $this->widget('TabMenu', [
				'style' => TabMenu::STYLE_PILLS,
				'id' => 'analyzer-tab-menu',
				'items' => $analyzers,
				'special' => 'analyzer-task-menu-item',
				'active' => array_values($analyzers)[0]['data-id']
			]) ?>
			<hr>
			<div class="row no-margin direction-view-wrapper">
				<div class="col-xs-6 no-padding">
					<?php $this->widget('Laboratory_Widget_DirectionPanel', [
						'title' => 'Все направления на анализ',
						'body' => $this->createWidget('GridTable', [
							'provider' => new Laboratory_Grid_Queue()
						]),
						'panelClass' => 'panel panel-default no-select',
						'status' => Laboratory_Direction::STATUS_LABORATORY,
					]) ?>
				</div>
				<div class="col-xs-6" style="padding-right: 0">
					<?php if ($analyzers['empty']['disabled']) { $analyzers = []; } ?>
					<?php foreach ($analyzers as $class => $analyzer): ?>
						<?php $first = !isset($first) ?>
						<div class="col-xs-12 no-padding laboratory-tab-container" id="<?= $analyzer['data-tab'] ?>" style="<?= $first ? 'display: block;' : 'display: none;' ?>">
							<?php $this->widget('Panel', [
								'title' => $this->getWidget('ControlMenu', [
									'controls' => [
										'analyzer-queue-start-button' => [
											'label' => 'Начать',
											'icon' => 'glyphicon glyphicon-play',
											'class' => 'btn btn-default'
										],
										'analyzer-queue-stop-button' => [
											'label' => 'Остановить',
											'icon' => 'glyphicon glyphicon-stop',
											'class' => 'btn btn-default'
									 	],
									],
									'mode' => ControlMenu::MODE_BUTTON
								]),
								'body' => CHtml::tag('h3', [
										'class' => 'text-center'
									], 'Пусто') . CHtml::tag('ul', [
										'class' => 'nav nav-pills nav-stacked analyzer-queue-container'
									], ''),
								'controls' => [
									'analyzer-queue-clear-button' => [
										'label' => 'Очистить',
										'icon' => 'glyphicon glyphicon-refresh',
										'class' => 'btn btn-warning'
									],
								],
								'controlMode' => ControlMenu::MODE_BUTTON,
								'titleWrapperClass' => 'col-xs-6 text-left no-padding',
								'controlsWrapperClass' => 'col-xs-6 text-right no-padding',
								'contentClass' => 'col-xs-12 no-padding no-margin panel-content',
								'id' => 'analyzer-task-viewer',
								'footer' => CHtml::tag('div', [
									'class' => 'progress'
								], CHtml::tag('div', [
									'class' => 'progress-bar progress-bar-striped active',
									'role' => 'progressbar',
									'style' => 'width: 0;'
								], CHtml::tag('span', [
									'class' => 'cr-only'
								], '')))
							]); ?>
							<hr>
							<div class="btn-group">
								<button class="btn btn-default laboratory-analyzer-info-button btn-lg" data-toggle="modal" data-target="#laboratory-modal-queue-guide">
									<span class="glyphicon glyphicon-info-sign"></span>
									&nbsp;Помощь
								</button>
								<button class="btn btn-default laboratory-analyzer-info-button btn-lg" data-id="<?= $analyzer['data-id'] ?>">
									<span class="glyphicon glyphicon-list-alt"></span>&nbsp;<?= $analyzer['label'] ?>
								</button>
							</div>
							<hr>
							<div class="laboratory-clock-wrapper"></div>
						</div>
					<?php endforeach ?>
				</div>
			</div>
		</div>
		<div id="laboratory-ready-grid-wrapper" class="no-display text-left">
			<hr>
			<?php $this->widget('Laboratory_Widget_DirectionPanel', [
				'title' => 'Завершенные направления',
				'body' => $this->createWidget('GridTable', [
					'provider' => new Laboratory_Grid_Direction([
						'status' => Laboratory_Direction::STATUS_READY,
						'menu' => [
							'controls' => [
								'direction-result-icon' => [
									'icon' => 'glyphicon glyphicon-eye-open',
									'label' => 'Проверить результаты'
								],
								'direction-show-icon' => [
									'icon' => 'glyphicon glyphicon-list',
									'label' => 'Открыть направление'
								],
							],
							'mode' => ControlMenu::MODE_ICON
						]
					]),
				]),
				'search' => false,
				'status' => Laboratory_Direction::STATUS_READY
			]) ?>
			<hr>
			<button class="btn btn-primary btn-lg">
				<span class="glyphicon glyphicon-print"></span>&nbsp;Печать
			</button>
		</div>
	</div>
</div>
<span class="glyphicon glyphicon-arrow-right laboratory-tr-pointer" style="display: none;"></span>