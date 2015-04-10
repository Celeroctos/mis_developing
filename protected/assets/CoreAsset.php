<?php

class CoreAsset extends AssetBundle {

	public $js = [
		"js/core/core.js",
		"js/core/panel.js",
		"js/core/loading.js",
		"js/core/form.js",
		"js/core/multiple.js",
		"js/core/message.js",
		"js/core/address.js",
		"js/core/table.js",
	];

	public $css = [
		"css/message.css",
		"css/panel.css",
		"css/multiple.css",
		"css/table.css",
	];

	public $dependencies = [
		"DefaultAsset"
	];
}