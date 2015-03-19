<?php

class LaboratoryModule2 extends CWebModule {

    protected function init() {
        LaboratoryAsset::register();
    }
}