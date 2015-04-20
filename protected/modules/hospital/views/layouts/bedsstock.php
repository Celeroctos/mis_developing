<?php
    MainAsset::register();
    HospitalAsset::register();
?>
<!DOCTYPE html>
<head>
    <title>МИС МОНИИАГ</title>
    <meta http-equiv="Cache-Control" content="max-age=3600, must-revalidate">
    <script type="text/javascript">
        var globalVariables = {
            baseUrl : '<?php echo Yii::app()->request->baseUrl; ?>'
        };
    </script>
    <?= AssetBundleManager::getManager()->render(); ?>
	<script type="text/javascript">
        var timer = setInterval(function() {
            var module = misEngine.create('component.module.hospital.bedsstock');
            if(module != -1) {
                clearInterval(timer);
                module.run();
            }
        }, 200);
	</script>
    <style>
        body {
            font-size: <?php echo Yii::app()->user->fontSize; ?>px !important;
        }
        .errorText {
            font-size: <?php echo Yii::app()->user->fontSize + 2; ?>px;
        }
    </style>
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
</head>
<body>
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
		<nobr><span class="buttonUp"><span class ="glyphicon glyphicon-chevron-up buttonUpSign"></span><span class="buttonUpText">Наверх</span></span><nobr>
</div>
<?php $this->widget('FooterPanel'); ?>
</body>

</html>