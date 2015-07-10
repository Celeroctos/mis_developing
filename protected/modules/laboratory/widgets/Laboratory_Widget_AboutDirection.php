<?php

class Laboratory_Widget_AboutDirection extends Widget {

	public $direction = null;

	public function run() {
		if (empty($this->direction)) {
			throw new CException('Can\'t resolve empty direction identification number');
		} else if (!$direction = Laboratory_Direction::model()->findByPk($this->direction)) {
			throw new CException('Can\'t resolve direction identification number "'. $this->direction .'"');
		}
        print Html::beginTag('div', [
            'class' => 'row no-margin',
        ]);
		$this->render('Laboratory_Widget_AboutDirection', [
			'direction' => $direction,
		]);
        print Html::endTag('div');
	}
}