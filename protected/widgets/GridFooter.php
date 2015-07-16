<?php

class GridFooter extends Widget {

	/**
	 * @var GridProvider class instance, which provides
	 * 	manipulations with ActiveRecord models
	 */
	public $provider = null;

	/**
	 * @var bool flag, which enables or disables
	 * 	displaying of pagination element
	 */
	public $withPagination = true;

	/**
	 * @var bool flag, which enables or disables
	 * 	displaying of search element
	 */
	public $withSearch = true;

	/**
	 * @var bool flag, which enables or disables
	 * 	displaying of element with limits
	 */
	public $withLimit = true;

	/**
	 * Render widget to return just rendered
	 * footer content for Grid widget
	 */
	public function run() {
		print Html::beginTag("tfoot", [
			"class" => "table-footer core-table-footer"
		]);
		print Html::beginTag("tr");
		print Html::beginTag("td", [
			"colspan" => count($this->provider->columns) + ($this->provider->getMenu() != false ? 1 : 0),
			"class" => "col-xs-12 no-padding"
		]);
		print Html::beginTag("div", [
			"class" => "col-xs-9 text-left no-padding"
		]);
		if ($this->withPagination) {
			$this->renderPagination();
		}
		print Html::endTag("div");
		print Html::beginTag("div", [
			"class" => "col-xs-1 text-center"
		]);
		print Html::endTag("div");
		print Html::beginTag("div", [
			"class" => "col-xs-2 text-right"
		]);
		if ($this->withLimit && count($this->provider->getData()) > 0) {
			$this->renderLimit();
		}
		print Html::endTag("div");
		print Html::endTag("td");
		print Html::endTag("tr");
		print Html::endTag("tfoot");
	}

	public function renderPagination() {
		if ($this->provider->pagination != false) {
			$this->widget("LinkPager", [
				"pages" => $this->provider->getPagination()
			]);
		}
	}

	public function renderLimit() {
		if ($this->provider->limits !== false) {
			$list = [];
			foreach ($this->provider->limits as $value) {
				$list[$value] = $value;
			}
			if ($this->provider->getPagination() != null) {
				$limit = $this->provider->getPagination()->pageSize;
			} else {
				$limit = $this->provider->limits[0];
			}
			if ($limit !== null && !isset($list[$limit])) {
				$list = [ $limit => $limit ] + $list;
			} else if ($limit === null) {
				$limit = "";
			}
			print Html::dropDownList("limits", $limit, $list, [
				"class" => "form-control",
				"onchange" => "$(this).table('limit', $(this).val())",
				"style" => "width: 75px; margin-left: calc(100% - 75px);"
			]);
		}
	}
}