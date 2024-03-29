<?php

class MainAsset extends AssetBundle {

    public $js = [
        "js/main.js",
        "js/timecontrol.js",
        "js/datecontrol.js",
        "js/keyboard.js",
        "js/pagination.js",
		"js/side-menu.js",
        "js/keyboardcnf.js",
        "js/ajaxbutton.js",
    ];

    public $css = [
		"css/main.less"
    ];

    public $dependencies = [
        "DefaultAsset",
        "CoreAsset",
    ];
} 