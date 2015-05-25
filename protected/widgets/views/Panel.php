<?php
/**
 * @var $this Panel
 * @var $content string
 * @var $widget string
 * @var $parameters string
 */
?>

<div class="<?= $this->panelClass ?>" <?= $this->renderAttributes() ?>>
    <div class="<?= $this->headingClass ?>">
		<div class="<?= $this->titleWrapperClass ?>">
			<span <?= $this->collapsible ? "onclick=\"$(this).panel('toggle')\"" : "" ?> class="<?= $this->titleClass ?>"><?= $this->title ?></span>
		</div>
		<div class="<?= $this->controlsWrapperClass ?>">
			<?php $this->renderControls() ?>
		</div>
    </div>
    <div class="<?= $this->bodyClass ?>" <?= $this->collapsed ? "style=\"display: none\"" : "" ?>>
		<div class="row no-padding no-margin">
			<div class="<?= $this->contentClass ?>">
				<?= $content ?>
			</div>
		</div>
	</div>
	<?php if (!empty($this->footer)): ?>
		<div class="panel-footer"><?= $this->footer ?></div>
	<?php endif ?>
</div>