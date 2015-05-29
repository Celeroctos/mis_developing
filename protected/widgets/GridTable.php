<?php

class GridTable extends Widget {

	/**
	 * @var string - Unique identification value of current
	 * 	table, by default it generates automatically with prefix
	 *
	 * @see UniqueGenerator::generate
	 */
	public $id = null;

	/**
	 * @var GridProvider class instance, which provides
	 * 	manipulations with ActiveRecord models
	 */
	public $provider = null;

	/**
	 * @var array with extra table configuration, only
	 * 	for internal usage
	 * @internal
	 */
	public $config = [];

	/**
	 * Run widget to catch just rendered table's content
	 * and return it as widget result
	 *
	 * @return string with just rendered table
	 * @throws Exception
	 */
	public function run() {
		if (is_string($this->provider)) {
			$this->provider = new $this->provider();
			if (!$this->provider instanceof GridProvider) {
				throw new Exception("Table provider must be an instance of [app\\core\\Table] class");
			}
		}
		$this->provider->getData();
		$this->config = array_merge($this->config, $this->provider->config);
		$this->renderTable();
	}

	/**
	 * Get extra configuration for table element, it stores
	 * information about widget and it's provider
	 *
	 * @return array with table configuration
	 */
	public function getExtraConfig() {
		$extra = $this->provider->extra;
		foreach ($extra as $key => &$value) {
			if (is_array($value)) {
				$value = json_encode($value);
			}
		}
		return $extra + [
			"data-provider" => get_class($this->provider),
			"data-widget" => get_class($this),
		];
	}

	/**
	 * Render basic table element, which starts
	 * with <table> tag and ends with </table>, it
	 * invokes sub-methods, which renders header,
	 * body and footer elements
	 */
	public function renderTable() {
		print Html::beginTag("table", $this->getExtraConfig() + [
				"class" => $this->provider->tableClass,
				"id" => $this->provider->id != null ? $this->provider->id : $this->getId(),
				"data-config" => json_encode($this->config)
			]);
		if ($this->provider->hasHeader) {
			$this->renderHeader();
		}
		$this->renderBody();
		if ($this->provider->hasFooter && count($this->provider->getData()) > 0) {
			$this->widget("GridFooter", [
				"provider" => $this->provider
			]);
		}
		print Html::endTag("table");
	}

	public function renderHeader() {
		print Html::beginTag("thead", [
			"class" => "table-header core-table-header"
		]);
		foreach ($this->provider->columns as $key => $attributes) {
			$prepared = $this->prepareHeader($attributes);
			$options = [
				"data-key" => $key
			];
			if ($this->provider->getSort() != false) {
				if ($prepared["relation"] && $this->provider->getTotalItemCount() > 0) {
					/* @var $related CActiveRecord */
					$related = $this->provider->getData()[0];
					$r = "";
					foreach (explode(".", $prepared["relation"]) as $r) {
						$related = $related->getRelated($r);
					}
					$column = $r.'.'.$key;
				} else {
					$column = $key;
				}
				if (isset($this->provider->getSort()->defaultOrder[$column]) && ($d = $this->provider->getSort()->defaultOrder[$column]) == CSort::SORT_ASC) {
					$options["onclick"] = "$(this).table('order', '-$column')";
				} else {
					$options["onclick"] = "$(this).table('order', '$column')";
				}
				/* if (!in_array($key, array_keys($this->provider->getSort()->attributes))) {
					unset($options["onclick"]);
				} */
			} else {
				$column = null;
			}
			print Html::beginTag("td", $options + $attributes + [
					"align" => "left"
				]);
			print Html::tag("b", [], $prepared["label"]);
			if ($column != null) {
				$this->renderChevron($column);
			}
			print Html::endTag("td");
		}
		if ($this->provider->menu != false) {
			print Html::tag("td", [
				"width" => $this->provider->menuWidth
			], "");
		}
		print Html::endTag("thead");
	}

	public function renderChevron($key) {
		if (!is_string($key) || !isset($this->provider->getSort()->defaultOrder[$key])) {
			return ;
		} else {
			$direction = $this->provider->getSort()->defaultOrder[$key];
		}
		if ($direction == CSort::SORT_ASC) {
			$class = "{$this->provider->chevronDownClass} table-order table-order-asc";
		} else {
			$class = "{$this->provider->chevronUpClass} table-order table-order-desc";
		}
		print "&nbsp;".Html::tag("span", [
				"class" => $class
			], "");
	}

	/**
	 * Render table controls for each row
	 */
	public function renderMenu() {
		if ($this->provider->menu == false) {
			return ;
		}
		if ($this->provider->menuAlignment === null) {
			if ($this->provider->getMenu()->mode == ControlMenu::MODE_MENU) {
				$alignment = "right";
			} else {
				$alignment = "middle";
			}
		} else {
			$alignment = $this->provider->menuAlignment;
		}
		print Html::beginTag("td", [
			"align" => $alignment,
			"width" => $this->provider->menuWidth
		]);
		/** @var $menu ControlMenu */
		if ($this->provider->getMenu() != false) {
			$this->provider->getMenu()->run();
		}
		print Html::endTag("td");
	}

	public function renderBody() {
		$models = $this->provider->getData();
		print Html::beginTag("tbody", [
			"class" => "table-body"
		]);
		foreach ($models as $model) {
			$attributes = [
				"data-id" => $model[$this->provider->primaryKey],
				"class" => "table-row"
			];
			if ($this->provider->clickEvent != null) {
				$attributes["onclick"] = $this->provider->clickEvent."(this, '{$model[$this->provider->primaryKey]}')";
			}
			if ($this->provider->doubleClickEvent != null) {
				$attributes["ondblclick"] = $this->provider->doubleClickEvent."(this, '{$model[$this->provider->primaryKey]}')";
			}
			$this->renderRow($model, $attributes);
		}
		if (empty($models)) {
			print Html::tag("tr", [], Html::tag("td", [
				"colspan" => count($this->provider->columns) + ($this->provider->menu != false ? 1 : 0),
			], Html::tag("h5", [ "class" => "text-center" ], $this->provider->textNoData)));
		}
		print Html::endTag("tbody");
	}

	public function renderRow($model, $attributes) {
		print Html::beginTag("tr", $attributes);
		foreach ($this->provider->columns as $key => $attributes) {
			$prepared = $this->prepareHeader($attributes);
			/* @var $model CActiveRecord */
			if ($prepared["relation"]) {
				$related = $model;
				foreach (explode(".", $prepared["relation"]) as $r) {
					$related = $related->getRelated($r);
				}
			} else {
				$related = $model;
			}
			if ($prepared["format"]) {
				$list = [ $related ];
				Fetcher::format($prepared["format"], $list);
				$value = $list[0];
			} else {
				$value = $related->$key;
			}
			print Html::tag("td", [
					"class" => "table-cell"
				] + $attributes + [
					"align" => "left",
				], $value);
		}
		$this->renderMenu();
		print Html::endTag("tr");
	}

	/**
	 * Prepare column's headers for usage, it fetch
	 * useful information from attributes array and
	 * returns it as required parameters
	 *
	 * @param $attributes array with column attributes
	 * 	from provider's column array
	 *
	 * @see app\core\Table::columns
	 *
	 * @return array with required attributes
	 */
	private function prepareHeader(&$attributes) {
		if (!is_array($attributes)) {
			$attributes = [
				"label" => $attributes
			];
		}
		if (isset($attributes["label"])) {
			$label = $attributes["label"];
		} else {
			$label = "";
		}
		if (isset($attributes["relation"])) {
			$relation = $attributes["relation"];
		} else {
			$relation = null;
		}
		if (isset($attributes["format"])) {
			$format = $attributes["format"];
		} else {
			$format = null;
		}
		unset($attributes["label"]);
		unset($attributes["relation"]);
		unset($attributes["format"]);
		return [
			"label" => $label,
			"relation" => $relation,
			"format" => $format,
		];
	}

	public function getAttributes($attributes = null, $excepts = []) {
		return parent::getAttributes($attributes, $excepts, [ "provider" ]);
	}
}