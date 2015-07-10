<?php

class Laboratory_Widget_DirectionEditor extends Widget {

    public $direction;

    public function init() {
        if (is_scalar($this->direction) && !$this->direction = Laboratory_Direction::model()->findByPk($this->direction)) {
            throw new CException('Unresolved direction identification number');
        } else if (empty($this->direction)) {
            throw new CException('Direction can\'t be empty');
        }
    }

    public function run() {
        $analysis = Laboratory_AnalysisType::model()->findWithParametersAndSamples(
            $this->direction->{'analysis_type_id'}
        );
        $parameters = Laboratory_Direction::model()->getAnalysisParameters(
            $this->direction->{'id'}
        );
        return $this->render('Laboratory_Widget_DirectionEditor', [
            'direction' => $this->direction,
            'parameters' => $parameters,
            'samples' => $analysis['samples'],
            'analysis' => $analysis['analysis']
        ]);
    }
}