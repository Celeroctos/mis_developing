<?php

class GuidesModule extends CWebModule {

    public function init() {
        $this->setModules([
			'laboratory' => [
				'class' => 'application.modules.guides.modules.laboratory.LaboratoryModule',
				'import'=> [
					'application.modules.guides.modules.laboratory.components.*',
					'application.modules.guides.modules.laboratory.controllers.*'
				]
			]
		]);
    }
}
