<?php
/**
 * @var $this Modal - Modal widget component for laboratory module
 */
?>

<div class="modal" tabindex="10" role="dialog" aria-hidden="true" id="<?=$this->id?>">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?=$this->title?></h4>
            </div>
            <div class="modal-body" style="text-align: center;">
                <button type="button" class="btn btn-default" style="margin-right: 10px;" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-button" data-dismiss="modal">Удалить</button>
            </div>
        </div>
    </div>
</div>