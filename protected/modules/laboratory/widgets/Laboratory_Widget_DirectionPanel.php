<?php

class Laboratory_Widget_DirectionPanel extends Panel {

	/**
	 * @var string - Default panel's date
	 */
	public $date = null;

	public $search = true;

	public $controlsWrapperClass = 'col-xs-6 text-right no-padding';
	public $titleWrapperClass = 'col-xs-6 text-left no-padding';

	/**
	 * @var int - Default panel's control mode is button, don't
	 * 	change it, cuz it uses inline button elements
     *
	 * @internal
	 */
	public $controlMode = ControlMenu::MODE_BUTTON;

	/**
	 * @var int - Direction status (for extra menu)
	 */
	public $status = Laboratory_Direction::STATUS_TREATMENT_ROOM;

	public function init() {
		if (empty($this->date) && $this->date !== false) {
			$this->date = date('Y-m-d');
		}
        if (empty($this->body)) {
            $this->body = $this->createWidget('GridTable', [
                'provider' => new Laboratory_Grid_Direction([
                    'status' => $this->status, 'date' => $this->date,
                ])
            ]);
        }
		if ($this->body instanceof GridTable) {
			$provider = is_string($this->body->provider) ? $this->body->provider :
				get_class($this->body->provider);
			$config = json_encode($this->body->provider->config);
		} else {
			$provider = null;
			$config = null;
		}
		if ($this->search) {
			$this->controls = [
				'panel-search-button' => [
					'class' => 'btn btn-default',
					'icon' => 'glyphicon glyphicon-search',
					'label' => 'Фильтр',
					'data-container' => '#'.$this->getId(),
					'data-trigger' => 'click',
					'data-toggle' => 'popover',
					'data-placement' => 'bottom',
					'data-html' => 'true',
                    'data-animation' => 'false',
					'data-content' => $this->getWidget('Laboratory_Widget_DirectionSearch', [
						'widget' => get_class($this->body),
						'provider' => $provider,
						'config' => $config
					])
				],
			];
		} else {
			$this->controls = [];
		}
		if ($this->date !== false) {
			$this->controls += [
				'panel-date-button' => [
					'class' => 'btn btn-default',
					'icon' => 'glyphicon glyphicon-calendar',
					'label' => 'Дата&nbsp;(' . CHtml::tag('span', [
							'class' => 'direction-date'
						], $this->date) . ')',
				],
				'panel-update-button' => [
					'class' => 'btn btn-default',
					'icon' => 'glyphicon glyphicon-refresh',
					'onclick' => '$(this).panel("update")',
					'label' => 'Обновить',
				]
			];
		} else {
			$this->controls += [
				'panel-update-button' => [
					'class' => 'btn btn-default',
					'icon' => 'glyphicon glyphicon-refresh',
					'onclick' => '$(this).panel("update")',
					'label' => 'Обновить',
				]
			];
		}
		parent::init();
	}
}