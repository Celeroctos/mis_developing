<?php

class TabMenu extends Widget {

	const STYLE_TABS = 'nav nav-tabs';
	const STYLE_TABS_JUSTIFIED = 'nav nav-tabs nav-justified';
	const STYLE_TABS_STACKED = 'nav nav-tabs nav-stacked';
	const STYLE_PILLS = 'nav nav-pills';
	const STYLE_PILLS_JUSTIFIED = 'nav nav-pills nav-justified';
	const STYLE_PILLS_STACKED = 'nav nav-pills nav-stacked';
    const STYLE_GREEN = 'nav nav-pills nav-green';
    const STYLE_GREEN_JUSTIFIED = 'nav nav-pills nav-justified nav-green';
    const STYLE_GREEN_STACKED = 'nav nav-pills nav-stacked nav-green';

	/**
	 * @var string identification number of current
	 * 	tab menu widget
	 */
	public $id = null;

	/**
	 * @var array - Array with items, where key is class and
	 * 	item is array with href, label, options and sub items
	 * + label - Displayable item label
	 * + [options] - Array with HTML options
	 * + [href] - Href for [a] tag
	 * + [items] - Sub items for DropDown list
	 */
	public $items = [];

	/**
	 * @var string - Default navigation tabs
	 * 	style, see TabMenu styles
	 */
	public $style = self::STYLE_TABS;

	/**
	 * @var string with special class that automatically
	 *  adds to each pill item (not dropdown menu)
	 */
	public $special = false;

	public $active = false;

	/**
	 * Run widget to return just rendered content
	 */
	public function run() {
		$this->renderItems($this->items);
	}

	/**
	 * Render tab menu items, if some item has sub-items, then
	 * it renders it again recursively
	 * @param array $items - Array with items
	 * @param bool $root - Is list with items root
	 */
	public function renderItems($items, $root = true) {
		print Html::openTag('ul', [
			'class' => ($root ? $this->style : 'dropdown-menu'),
			'role' => 'menu',
			'id' => $this->getId()
		]);
		foreach ($items as $class => $item) {
			if (isset($item['href'])) {
				$href = $item['href'];
			} else {
//				$href = '#'.$class;
                $href = 'javascript:void(0)';
			}
			$options = [
				'role' => 'presentation'
			];
			if ($root && !empty($this->special)) {
				if (isset($options['class'])) {
					$options['class'] .= ' $this->special';
				} else {
					$options['class'] = $this->special;
				}
			}
			if (isset($item['disabled']) && $item['disabled'] == true) {
				$options['class'] .= ' disabled';
			}
			if (isset($item['active']) && $item['active'] == true) {
				$options['class'] .= ' active';
			}
			if (isset($item['items']) && count($item['items']) > 0) {
				$options['class'] .= ' dropdown';
			}
			if (isset($item['label'])) {
				$label = $item['label'];
			} else {
				$label = '';
			}
			if (isset($item['icon'])) {
				$label = CHtml::tag('span', [ 'class' => $item['icon'] ], '') .'&nbsp;'. $label;
			}
			if ($this->active !== false && $this->active == $class) {
				$options['class'] .= ' active';
			}
			unset($item['label']);
			print Html::openTag('li', $options);
			print Html::link($label, $href, $item);
			if (isset($item['items']) && count($item['items']) > 0) {
				$this->renderItems($item['items'], false);
			}
			print Html::closeTag('li');
		}
		print Html::closeTag("ul");
	}
}