<?php

class LaboratoryModule extends CWebModule {

	protected function init() {
		GuideLaboratoryAsset::register();
	}
}