<?php

class Laboratory_Widget_AnalysisParameterChecker extends Widget {

	/**
	 * @var array with parameters for analysis type
	 */
	public $parameters = [];

	/**
	 * @var string name of checkbox form field
	 */
	public $name = 'Laboratory_Form_DirectionEx[analysis_parameters][]';

	public $readonly = false;

	public function run() {
		print CHtml::openTag('div', [
			'style' => 'margin-bottom: 10px; margin-left: 10px; font-size: 14px;',
			'class' => 'analysis-type-params-wrapper'
		]);
		foreach ($this->parameters as $param) {
			print CHtml::openTag('div', [
				'class' => 'checkbox text-left'
			]);
			print CHtml::openTag('label');
			$options = [
				'type' => 'checkbox',
				'value' =>$param['id'],
				'name' => $this->name
			];
			if (isset($param['checked']) && $param['checked']) {
				$options['checked'] = 'checked';
			}
			if ($this->readonly) {
				$options['disabled'] = 'true';
			}
			print CHtml::tag('input', $options, '');
			print '&nbsp;'.trim($param['name']).' ('.trim($param['short_name']).')';
			print CHtml::closeTag('label');
			print CHtml::closeTag('div');
		}
		print CHtml::closeTag('div');
	}
}