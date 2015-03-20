<?php

class AnalysisTypeController extends GController {

	/**
	 * Override that method to return model for guide
	 * controller
	 * @return GActiveRecord - Active record
	 */
	public function getModel() {
		return new AnalysisType();
	}
}
