<?
/**
 * @var $this GuideValueEditor - Widget's instance
 * @var $columns Array - Array with guide columns
 * @var $values Array - Array with guide values
 */
?>

<div class="col-xs-12 col-xs-offset-0 guide-values-container">
    <table class="table" width="100%">
        <thead>
        <tr>
            <?php foreach ($columns as $column): ?>
                <td><b><?= $column->name ?></b></td>
            <?php endforeach; ?>
            <td></td>
        </tr>
        </thead>
        <tbody>
        <?php if (count($values) == 0): ?>
            <?php for ($i = 0; $i < GuideValueEditor::DEFAULT_COUNT; $i++): ?>
                <tr>
                    <?php foreach ($columns as $column): ?>
                        <td data-position="<?= $column->position ?>">
                            <?= $this->renderField($column->type, $column->name, $column->default_value,
								$column->lis_guide_id, $column->display_id) ?>
                        </td>
                    <?php endforeach; ?>
                    <td><span style="font-size: 15px; margin-top: 7px" class="glyphicon glyphicon-remove glyphicon-red remove"></span></td>
                </tr>
            <?php endfor; ?>
        <?php else: ?>
            <?php foreach ($values as $value): ?>
                <tr data-id="<?= isset($value[0]) > 0 ? $value[0]["guide_row_id"] : "" ?>">
                    <?php foreach ($columns as $column): ?>
                        <?php if (isset($value[$column->position - 1])): ?>
                            <td data-position="<?= $column->position ?>" data-id="<?= $value[$column->position - 1]["id"] ?>">
                                <?= $this->renderField($column->type, $column->name, $value[$column->position - 1]["value"],
                                    $column->lis_guide_id, $column->display_id) ?>
                            </td>
                        <?php else: ?>
                            <td data-position="<?= $column->position ?>">
                                <?= $this->renderField($column->type, $column->name, $column->default_value,
									$column->lis_guide_id, $column->display_id) ?>
                            </td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <td><span style="font-size: 15px; margin-top: 7px" class="glyphicon glyphicon-remove glyphicon-red remove"></span></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
    <div style="width: 100%; text-align: right">
        <a href="javascript:void(0)" id="guide-edit-add-fields">
            <span style="font-size: 20px" class="glyphicon glyphicon-plus"></span>
        </a>
    </div>
</div>