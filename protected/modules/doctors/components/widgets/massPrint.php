<?php
class massPrint extends CWidget {

    public $greetings = null;

    public function run()
    {
        $arrayCount = 0;
        foreach($this->greetings as $greeting) {

            // ������� �� ��������� ������� ��������. ���� ���� ��������� - ����� ���� �� ������ ������ ��������
            $pageBreaking = 'style="page-break-after:always"';
            //var_dump($arrayCount);
            //var_dump(count($greeting));
            if ($arrayCount==count($this->greetings)-1)
                $pageBreaking = '';

            ?><div <?php echo $pageBreaking; $arrayCount ?>><?php
            // �������� ������ ������ ������ �����
            $this->render('application.modules.doctors.views.print.greeting', array(
                'templates' => $greeting['templates'],
                'greeting' => $greeting['greeting'],
                'notPrintPrintBtn' => '1'
            ));
            ?></div><?php

            $arrayCount++;
        }
    }

}
?>