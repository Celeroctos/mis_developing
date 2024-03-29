<?php
/**
 * @var string $content
 */
MainAsset::register();
?>
<!DOCTYPE html>
<html>
<head>
    <title>МИС МОНИИАГ</title>
    <script type="text/javascript">
        var globalVariables = {
            baseUrl : '<?= Yii::app()->request->baseUrl; ?>',
			widget: '<?= Widget::createUrl("widget") ?>',
			table: '<?= Widget::createUrl("table") ?>',
			panel: '<?= Widget::createUrl("panel") ?>'
        };
    </script>
	<?= AssetBundleManager::getManager()->render() ?>
</head>
<body>
<?php $this->widget("ConfirmDelete") ?>
<?php $this->widget('application.widgets.MainNavBar') ?>
<div class="container-fluid" id="content">
    <div class="row main-container">
        <div class="col-xs-2">
            <?php $this->widget('application.widgets.SideMenu') ?>
        </div>
        <div class="col-xs-9">
            <?php echo $content; ?>
        </div>
    </div>
</div>
<div class ="buttonUpContainer">
	<nobr>
		<span class="buttonUp">
			<span class="glyphicon glyphicon-chevron-up buttonUpSign"></span>
			<span class="buttonUpText">Наверх</span>
		</span>
	</nobr>
</div>
</body>
</html>