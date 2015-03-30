<?php

class DoctorsModule extends CWebModule {

	protected function init() {
		DoctorScheduleAsset::register();
	}
}