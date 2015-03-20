<? MainAsset::register() ?>
<!DOCTYPE html>
<html>
<head>
	<title>МИС МОНИИАГ</title>
	<script type="text/javascript">
		var globalVariables = {
			baseUrl : '<?php echo Yii::app()->request->baseUrl; ?>'
		};
	</script>
	<?= AssetBundleManager::getManager()->render() ?>
</head>
<body>
<?php $this->widget('application.widgets.MainNavBar') ?>
<div class="container-fluid" id="content">
	<div class="row main-container">
		<div class="col-xs-2">
			<?php $this->widget('application.widgets.SideMenu') ?>
		</div>
		<div class="col-xs-9">
			<?php $this->widget('application.widgets.GuidesTabMenu') ?>
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
	<nobr>
</div>
</body>
</html>