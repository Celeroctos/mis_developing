<?php

class AnalysisResult extends Widget {

	/**
	 * @var int direction's identification number
	 */
	public $direction = null;

	public function run() {
		if (!$this->direction || !$this->direction = LDirection::model()->findByPk($this->direction)) {
			throw new CException('Unresolved direction\'s identification number ('.$this->direction.')');
		}  else if (!$analysis = LAnalysis::model()->findByAttributes([ 'direction_id' => $this->direction->getAttribute('id') ])) {
			throw new CException('Unresolved analysis\'s identification number ('.$this->direction->getAttribute('id').')');
		}
		$results = LAnalysisResult::model()->findAllByAttributes([
			'analysis_id' => $analysis->getAttribute('id')
		]);
		$allowed = LDirectionParameter::model()->findByDirection($this->direction->getAttribute('id'));
		return $this->render('AnalysisResult', [
			'direction' => $this->direction,
			'analysis' => $analysis,
			'results' => $results,
			'allowed' => CHtml::listData($allowed, 'id', 'short_name')
		]);
	}
}