<?php
/**
 * @var $this AnalyzerTaskViewer
 * @var $analyzers array
 */

$this->widget("TabMenu", [
	"items" => $analyzers,
	"special" => "analyzer-task-menu-item",
	"style" => TabMenu::STYLE_PILLS_JUSTIFIED
]);
?>
<div class="analyzer-queue-wrapper">
	<div class="analyzer-task-tab" style="display: block">
		<?php if (empty($analyzers)): ?>
			<h4 class="text-center" style="margin-top: 10px">Нет доступных анализаторов</h4>
		<?php else: ?>
			<h4 class="text-center" style="margin-top: 20px">Анализатор не выбран</h4>
		<?php endif ?>
	</div>
	<?php foreach ($analyzers as $key => $analyzer): ?>
		<div class="analyzer-task-tab" id="<?= $analyzer["data-tab"] ?>" style="display: none">
			<?php $this->widget("AnalyzerQueue") ?>
		</div>
	<?php endforeach ?>
</div>