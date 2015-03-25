<?php

class CoreAsset extends AssetBundle {

	public $js = [
		"js/core/core.js",
		"js/core/form.js",
		"js/core/multiple.js",
		"js/core/message.js",
		"js/core/address.js",
	];

	public $css = [
		"css/message.css",
		"css/multiple.css"
	];

	public $dependencies = [
		"MainAsset"
	];
}