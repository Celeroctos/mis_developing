<?php

class MainAsset extends AssetBundle {

    public $js = [
		"js/sidemenu.js",
        "js/main.js",
        "js/timecontrol.js",
        "js/datecontrol.js",
        "js/keyboard.js",
        "js/pagination.js",
        "js/keyboardcnf.js",
        "js/ajaxbutton.js",
    ];

    public $css = [
		"css/main.less"
    ];

    public $dependencies = [
        "DefaultAsset"
    ];
} 