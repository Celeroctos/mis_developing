<!DOCTYPE html>
<head>
    <title>МИС МОНИИАГ</title>
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/js/libs/bootstrap-3.0.0/less/bootstrap.less" rel="stylesheet/less" media="screen">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet/less" media="screen">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/js/libs/jquery-ui-bootstrap/css/custom-theme/jquery-ui-1.10.0.custom.css" rel="stylesheet" type="text/css" media="screen"  />
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/js/libs/jqGrid/css/ui.jqgrid.css" rel="stylesheet" type="text/css" media="screen"  />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/libs/plot/jquery.jqplot.min.css">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.less" rel="stylesheet/less" media="screen">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/hospital/medical_directions_form.less" rel="stylesheet/less" media="screen">
    <script type="text/javascript">
        var globalVariables = {
            baseUrl : '<?php echo Yii::app()->request->baseUrl; ?>'
        };
    </script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/libs/less-1.4.1.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/libs/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/libs/bootstrap-3.0.0/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/libs/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/libs/bootstrap-datetimepicker.ru.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/libs/jquery.selection.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/libs/jqGrid/js/i18n/grid.locale-ru.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/libs/jqGrid/js/jquery.jqGrid.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/libs/jquery-browser.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/libs/jquery.keyfilter-1.7.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/main.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/datecontrol.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/timecontrol.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/keyboard.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/keyboardcnf.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/pagination.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/chooser.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/consilium.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/patientAlarms.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/common.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/side-menu.js"></script>
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
	<nobr><span class="buttonUp"><span class ="glyphicon glyphicon-chevron-up buttonUpSign"></span><span class="buttonUpText">Наверх</span></span></nobr>
</div>
<!--<div class="consilium-cont">
    <div class="panel-arrow">
        <span class="glyphicon glyphicon-expand"></span>
    </div>
	<h4>Консилиум</h4>
	<div class="main-window">
	</div>
	<input type="text" id="consilium-dialog-str" />
	<span class="glyphicon glyphicon-plus submit" title="Отправить сообщение"></span>
</div>
-->
<!--div class ="buttonUpContainer">
    <nobr><span class="buttonUp"><span class ="glyphicon glyphicon-chevron-up buttonUpSign"></span><span class="buttonUpText">Наверх</span></span></nobr>
</div>
<div class="alerts-cont">
    <div class="panel-arrow">
        <span class="glyphicon glyphicon-expand"></span>
    </div>
    <h4>Удаленные показания</h4>
    <div class="main-window">
    </div>
    <audio id="incomingIndicator" preload="auto">
        <source src="/content/audio/signal.wav" type="audio/wav">
    </audio>
</div-->
<?php $this->widget('FooterPanel'); ?>
</body>
</html>