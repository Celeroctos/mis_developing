<?php
/**
 * @var $this AnalyzerTaskViewer
 */

$this->widget("TabMenu", [
	"items" => [
		"analyzer-1" => [
			"label" => "MEK722"
		]
	],
	"special" => "analyzer-task-menu-item",
	"style" => TabMenu::STYLE_PILLS_JUSTIFIED
]);

print "<h3 class=\"text-center\">Analyzer Task Manager</h3>";