<?php
/**
 * @var $this Flipper
 */
?>
<style>
	.flip-container {
		perspective: <?= $this->perspective ?>;
	}
	.flip-container:hover .flipper, .flip-container.hover .flipper {
		transform: rotateY(180deg);
	}
	.flip-container,
	.flipper .front,
	.flipper .back {
		width: <?= $this->width ?>;
		height: <?= $this->height ?>;
	}
	.flipper {
		transition: <?= $this->velocity ?>;
		transform-style: preserve-3d;
		position: relative;
	}
	.flipper .front,
	.flipper .back {
		backface-visibility: hidden;
		position: absolute;
		top: 0;
		left: 0;
	}
	.flipper .front {
		z-index: 2;
		transform: rotateY(0deg);
	}
	.flipper .back {
		transform: rotateY(180deg);
	}
</style>
<div class="flip-container">
	<div class="flipper">
		<div class="front">
			<?= $this->front ?>
		</div>
		<div class="back">
			<?= $this->back ?>
		</div>
	</div>
</div>