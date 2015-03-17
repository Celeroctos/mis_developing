<ul class="nav nav-tabs default-margin-bottom">
    <li <?php echo $controller == 'analysisparam' ? 'class="active"' : ''; ?>>
        <?php echo CHtml::link('Параметры анализов', array('/guides/laboratory/analysisparam')) ?>
    </li>
    <li <?php echo $controller == 'analysistype' ? 'class="active"' : ''; ?>>
        <?php echo CHtml::link('Типы анализов', array('/guides/laboratory/analysistype')) ?>
    </li>
    <li <?php echo $controller == 'analysistypeparam' ? 'class="active"' : ''; ?>>
        <?php echo CHtml::link('Шаблоны анализов', array('/guides/laboratory/analysistypeparam')) ?>
    </li>
    <li <?php echo $controller == 'analyzertype' ? 'class="active"' : ''; ?>>
        <?php echo CHtml::link('Типы анализаторов', array('/guides/laboratory/analyzertype')) ?>
    </li>
    <li <?php echo $controller == 'analyzertypeanalysis' ? 'class="active"' : ''; ?>>
        <?php echo CHtml::link('Анализы доступные для анализаторов', array('/guides/laboratory/ananalyzertypeanalysis')) ?>
    </li>
    <li <?php echo $controller == 'AnalysisSample' ? 'class="active"' : ''; ?>>
        <?php echo CHtml::link('Типы образцов для анализов', array('/guides/laboratory/AnalysisSample')) ?>
    </li>
</ul>