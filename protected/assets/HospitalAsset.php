<?php

class HospitalAsset extends AssetBundle {

    public $js = [
        "js/engine/main.js",
    ];

    public $css = [
		"css/hospital/main.less"
    ];

    public $dependencies = [
        "MainAsset"
    ];
} 