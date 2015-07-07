<?php

class Laboratory_Widget_AboutDirection extends Widget {

	public $direction = null;

	public function run() {
		if (empty($this->direction)) {
			throw new CException('Can\'t resolve empty direction identification number');
		} else if (!$direction = LDirection::model()->findByPk($this->direction)) {
			throw new CException('Can\'t resolve direction identification number "'. $this->direction .'"');
		}
		$analysis = LAnalysisType::model()->findWithParametersAndSamples(
			$direction->{'analysis_type_id'}
		);
		$parameters = LDirection::model()->getAnalysisParameters(
			$direction->{'id'}
		);
		$this->render('AboutDirection', [
			'direction' => $direction,
			'parameters' => $parameters,
			'samples' => $analysis['samples'],
			'analysis' => $analysis['analysis']
		]);
	}
}