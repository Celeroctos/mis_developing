<?php
/**
 * @var Pagination $this - Self instance
 * @var int $offset - Start offset
 * @var int $step - Counter accumulator
 */
?>

<nav>
	<ul class="pagination">
		<li <?= $this->getClick($this->currentPage != 1, -1) ?>>
			<a href="javascript:void(0)" aria-label="Предыдущая">
				<span aria-hidden="true">&laquo;</span>
			</a>
		</li>
		<?php if ($this->currentPage > 1): ?>
			<li <?= $this->getClick(true, 1 - $this->currentPage) ?>>
				<a href="javascript:void(0)"><?= 1 ?>
					<span class="sr-only"></span>
				</a>
			</li>
			<?php if ($this->currentPage > 2): ?>
				<li <?= $this->getClick(false, 0) ?>>
					<a href="javascript:void(0)">...
						<span class="sr-only"></span>
					</a>
				</li>
			<?php endif; ?>
		<?php endif; ?>
		<?php for ($i = $this->currentPage + $offset, $j = -1; $i <= $this->totalPages && $j < $this->pageLimit; $i++, $j++): ?>
			<?php if ($i < 1 || $i > $this->totalPages) {
				continue;
			} ?>
			<li <?= $this->getClick(true, $i - $this->currentPage) ?>>
				<a href="javascript:void(0)"><?= $i ?>
					<span class="sr-only"></span>
				</a>
			</li>
		<?php endfor; ?>
		<?php if ($offset == 0): ?>
			<li <?= $this->getClick(false, 0) ?>>
				<a href="javascript:void(0)">...
					<span class="sr-only"></span>
				</a>
			</li>
			<li <?= $this->getClick(true, $this->totalPages - $this->currentPage) ?>>
				<a href="javascript:void(0)"><?= $this->totalPages ?>
					<span class="sr-only"></span>
				</a>
			</li>
		<?php endif; ?>
		<li <?= $this->getClick($this->currentPage != $this->totalPages, 1) ?>>
			<a href="javascript:void(0)" aria-label="Следующая">
				<span aria-hidden="true">&raquo;</span>
			</a>
		</li>
	</ul>
</nav>