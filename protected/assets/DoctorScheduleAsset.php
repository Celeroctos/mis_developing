<?php

class DoctorScheduleAsset extends AssetBundle {

	public $js = [
		"js/chooser.js",
		"js/doctors/patient.js",
		"js/doctors/comments.js",
		"js/doctors/categories.js",
		"js/tablecontrol.js",
		"js/twocolumncontrol.js",
		"js/ajaxbutton.js",
		"js/libs/jquery-json.js"
	];

	public $css = [
	];

	public $dependencies = [
		"MainAsset"
	];
}