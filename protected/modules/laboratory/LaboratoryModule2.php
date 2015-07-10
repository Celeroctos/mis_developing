<?php

class LaboratoryModule2 extends CWebModule {

    public $controllerNamespace = 'laboratory\controllers';

    protected function init() {
        LaboratoryAsset::register();
    }
}