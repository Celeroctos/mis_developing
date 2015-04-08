<?php
/**
 * @var Table $this - Table widget instance
 */
?>
<table class="<?= $this->tableClass ?> core-table" id="<?=$this->id?>" <?php $this->renderExtra() ?>>
	<thead class="core-table-header">
	<?php $this->renderHeader() ?>
	</thead>
	<tbody class="core-table-body">
	<?php $this->renderBody() ?>
	</tbody>
	<tfoot class="core-table-footer">
	<?php $this->renderFooter() ?>
	</tfoot>
</table>