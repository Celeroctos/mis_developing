<?php
/**
 * @var Table $this - Table widget instance
 * @var array $data - Array with all received data
 * @var string $parent - Parent's class name
 */
?>
<table class="table table-striped" data-condition="<?=$this->criteria->condition?>" data-parameters="<?=urlencode(serialize($this->criteria->params))?>" data-class="<?=$parent?>" id="<?=$this->id?>">
	<thead>
	<tr>
	<?php foreach ($this->header as $key => $value): ?>
		<td data-key="<?=$key?>" onclick="Table.order.call(this, '<?=$key?>')" align="left" <?=$value["id"] ? "id=\"{$value["id"]}\"" : ""?> <?=$value["class"] ? "class=\"{$value["class"]}\"" : ""?> style="cursor: pointer;<?=$value["style"]?>">
			<b><?=$this->header[$key]["label"]?></b>
			<?php if ($this->sort == $key && !$this->hideArrow): ?>
				<span class="glyphicon <?= $this->desc ? "glyphicon-chevron-up" : "glyphicon-chevron-down" ?>"></span>
			<?php endif; ?>
		</td>
	<?php endforeach; ?>
	<?php if (count($this->controls) > 0): ?>
		<td align="middle" style="width: 50px"></td>
    <?php endif; ?>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($data as $key => $value): ?>
		<tr data-id="<?= $value[$this->pk] ?>" <?= $this->click ? "onclick=\"{$this->click}(this, '{$value[$this->pk]}')\"" : "" ?>>
			<?php foreach ($this->header as $k => $v): ?>
				<td align="left"><?= isset($value[$k]) ? $value[$k] : "" ?></td>
			<?php endforeach; ?>
            <?php if (count($this->controls) > 0): ?>
                <td align="middle">
					<?php foreach ($this->controls as $c => $class): ?>
						<a href="javascript:void(0)"><span class="<?= $c." ".$class ?>"></span></a>
					<?php endforeach; ?>
                </td>
            <?php endif; ?>
		</tr>
	<?php endforeach; ?>
	<?php if (count($data) == 0): ?>
		<tr><td colspan="<?= count($this->header) + 1 ?>"><b>Нет данных</b></td></tr>
	<?php endif; ?>
	</tbody>
	<?php if (!$this->disablePagination): ?>
	<tfoot>
	<tr><td colspan="<?= count($this->header) + 1 ?>">
		<?php $this->widget("Pagination", [
			"limit" => 10,
			"action" => "Table.page.call",
			"page" => $this->page,
			"pages" => $this->pages
		]); ?>
	</td></tr>
	</tfoot>
	<?php endif; ?>
</table>