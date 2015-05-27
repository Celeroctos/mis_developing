<?php

class ImportHelper {

	public static function importGrid($class, $module = null) {
		return static::import($class, "grids", $module);
	}

	public static function importWidget($class, $module = null) {
		return static::import($class, "widgets", $module);
	}

	public static function import($class, $folder, $module = null) {
		$return = $class;
		if ($module == null && Yii::app()->getController()->getModule() != null) {
			$module = Yii::app()->getController()->getModule()->getId();
		} else if ($module instanceof CModule) {
			$module = $module->getId();
		}
		if ($module != null) {
			$class = 'application.modules.'.$module.'.'.$folder.'.'.$class;
		} else {
			$class = 'application.'.$folder.'.'.$class;
		}
		Yii::import($class);
		return $return;
	}
}