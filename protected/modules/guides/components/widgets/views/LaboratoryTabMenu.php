<ul class="nav nav-tabs default-margin-bottom">
    <?php if ( Yii::app()->user->checkAccess('guideEditAnalysisSample')) { ?>
    <li <?php echo $controller == 'analysissampletype' ? 'class="active"' : ''; ?>>
        <?php echo CHtml::link('Типы образцов для анализов', array('/guides/laboratory/analysissampletype')) ?>
    </li>
    <?php } ?>
    <?php if ( Yii::app()->user->checkAccess('guideEditAnalysisParam')) { ?>
    <li <?php echo $controller == 'analysisparam' ? 'class="active"' : ''; ?>>
        <?php echo CHtml::link('Параметры анализов', array('/guides/laboratory/analysisparam')) ?>
    </li>
    <?php } ?>
    <?php if ( Yii::app()->user->checkAccess('guideEditAnalysisType')) { ?>
    <li <?php echo $controller == 'analysistype' ? 'class="active"' : ''; ?>>
        <?php echo CHtml::link('Типы анализов', array('/guides/laboratory/analysistype')) ?>
    </li>
    <?php } ?>

    <?php if ( Yii::app()->user->checkAccess('guideEditAnalysisType')) { ?>
    <li <?php echo $controller == 'analysistypeparam' ? 'class="active"' : ''; ?>>
        <?php echo CHtml::link('Параметры для типов анализов', array('/guides/laboratory/analysistypeparam')) ?>
    </li>
    <?php } ?>
    <?php if ( Yii::app()->user->checkAccess('guideEditAnalysisType')) { ?>
    <li <?php echo $controller == 'analysistypesample' ? 'class="active"' : ''; ?>>
        <?php echo CHtml::link('Образцы для типов анализов', array('/guides/laboratory/analysistypesample')) ?>
    </li>
    <?php } ?>

    <?php if ( Yii::app()->user->checkAccess('guideEditAnalyzerType')) { ?>
    <li <?php echo $controller == 'analyzertype' ? 'class="active"' : ''; ?>>
        <?php echo CHtml::link('Типы анализаторов', array('/guides/laboratory/analyzertype')) ?>
    </li>
    <?php } ?>
    <?php if ( Yii::app()->user->checkAccess('guideEditAnalyzerTypeAnalysis')) { ?>
    <li <?php echo $controller == 'analyzertypeanalysis' ? 'class="active"' : ''; ?>>
        <?php echo CHtml::link('Анализы для типов анализаторов', array('/guides/laboratory/analyzertypeanalysis')) ?>
    </li>
    <?php } ?>
    <?php if ( Yii::app()->user->checkAccess('guideEditAnalyzerType')) { ?>
    <li <?php echo $controller == 'machine' ? 'class="active"' : ''; ?>>
        <?php echo CHtml::link('Анализаторы', array('/guides/laboratory/machine')) ?>
    </li>
    <?php } ?>
</ul>