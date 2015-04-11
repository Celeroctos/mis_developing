<?php
/**
 * @var $this Panel
 * @var $content string
 * @var $widget string
 * @var $parameters string
 */
?>

<div class="<?= $this->panelClass ?>" id="<?= $this->id ?>" <?= !empty($widget) ? "data-widget=\"$widget\"" : "" ?> <?= !empty($parameters) ? "data-parameters=\"$parameters\"" : "" ?>>
    <div class="<?= $this->headingClass ?>">
		<div class="<?= $this->titleWrapperClass ?>">
			<span class="<?= $this->titleClass ?>"><?= $this->title ?></span>
		</div>
		<div class="<?= $this->controlsWrapperClass ?>">
			<?php $this->renderControls() ?>
		</div>
    </div>
    <div class="<?= $this->bodyClass ?>">
		<div class="row no-padding no-margin">
			<div class="<?= $this->contentClass ?>">
				<?= $content ?>
			</div>
		</div>
	</div>
</div>