<?php

class AnalyzerTaskViewer extends Widget {

	public function run() {
		$this->render("AnalyzerTaskViewer", [
			"analyzers" => Analyzer::model()->listTabs()
		]);
	}
}