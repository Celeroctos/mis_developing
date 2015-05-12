<?php

class AnalyzerTaskViewer extends Widget {

	public function run() {
		$this->render("AnalyzerTaskViewer", [
			"analyzers" => $this->listAnalyzers()
		]);
	}

	public function listAnalyzers() {
		$items = [];
		foreach (Analyzer::model()->findAll() as $analyzer) {
			$items[$analyzer->{"id"}] = [
				"label" => $analyzer->{"name"},
				"data-tab" => UniqueGenerator::generate("tab"),
				"data-id" => $analyzer->{"id"}
			];
		}
		return $items;
	}
}