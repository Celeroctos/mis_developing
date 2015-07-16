<?php

class LinkPager extends CLinkPager {

	public $maxButtonCount = 5;

	protected function createPageButton($label, $page, $class, $hidden, $selected) {
		if($hidden || $selected) {
			$class.=' '.($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);
		}
		return '<li class="'.$class.'">'.CHtml::link($label, "javascript:void(0)", [
			"data-page" => $page, "onclick" => "$(this).table('page', '$page')"
		]).'</li>';
	}

	public function getCurrentPage($recalculate = true) {
		return $this->getPages()->currentPage;
	}
}