<?php

class Html extends CHtml {

	/**
	 * Render bootstrap's glyphicon element with title, if title not null then
	 * it will add tooltip title to it
	 * @param string $class - Name of glyphicon class
	 * @param string|null $title - Text for tooltip or null
	 * @param string $placement - Tooltip placement, default [left]
	 */
	public static function glyphicon($class, $title = null, $placement = "left") {
		$options = [
			"class" => $class
		];
		if ($title != null) {
			$options["data-toggle"] = "tooltip";
			$options["title"] = $title;
			$options["data-placement"] = $placement;
			$options["onmouseenter"] = "console.log(this);";
		}
		print CHtml::tag("span", $options);
	}
}