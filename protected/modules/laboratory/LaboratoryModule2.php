<?php

class LaboratoryModule2 extends CWebModule {

    protected function init() {
        AssetBundleManager::getManager()->register("LaboratoryAsset");
    }
}