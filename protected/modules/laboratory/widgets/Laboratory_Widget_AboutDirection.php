<?php

class Laboratory_Widget_AboutDirection extends Widget {

	public $direction = null;

	public function run() {
		if (empty($this->direction)) {
			throw new CException('Can\'t resolve empty direction identification number');
		} else if (!$direction = Laboratory_Direction::model()->findByPk($this->direction)) {
			throw new CException('Can\'t resolve direction identification number "'. $this->direction .'"');
		}
		$analysis = Laboratory_AnalysisType::model()->findWithParametersAndSamples(
			$direction->{'analysis_type_id'}
		);
		$parameters = Laboratory_Direction::model()->getAnalysisParameters(
			$direction->{'id'}
		);
		$this->render('Laboratory_Widget_AboutDirection', [
			'direction' => $direction,
			'parameters' => $parameters,
			'samples' => $analysis['samples'],
			'analysis' => $analysis['analysis']
		]);
	}
}