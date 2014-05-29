<?php
class SheduleRestDay extends MisActiveRecord {
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'mis.shedule_rest_days';
    }

    public static function deleteDates($datesToDel)
    {
        // ���������� ������ �� ���
        $datesString = "";

        for ($i=0;$i<count($datesToDel);$i++ )
        {
            $datesString .= ("'".$datesToDel[$i]."'");
            // ���� �� ��������� �������� - ������������� �������� � �����
            if ($i!=count($datesToDel)-1)
            {
                $datesString .= ",";
            }


        }
        // ���� ������ ������� - �������
        if ($datesString=="") return;

        // ������� ���
        $command = Yii::app()->db->createCommand(
            "DELETE FROM mis.shedule_rest_days srd
            WHERE
                SUBSTR(CAST(srd.date AS CHARACTER VARYING),0,11) in (" . $datesString .  ")
            "
        );

        @$command->query();
    }

    public static function writeAllRestDays($dataToWrite)
    {
        $rowNumber = 0;
        $sql = "INSERT INTO mis.shedule_rest_days (date, doctor_id, type) VALUES ";
        $keys = array_keys($dataToWrite);

        for ($j=0;$j<count($dataToWrite);$j++)
        {
            $key = $keys[$j];
            $oneDate = $dataToWrite[$key];

            // ���������� ��������
            for ($i=0;$i<count($oneDate);$i++)
            {
                // ���� ������� �� ������ - ������ ����� ��� ��������
                if ($rowNumber > 0)
                {
                    $sql .= ',';
                }
                $rowNumber++;
                $sql .= "(";
                $sql .= ("CAST('" . $key . "' AS TIMESTAMP),");
                $sql .= ($oneDate[$i]['doctor'].',');
                $sql .= ($oneDate[$i]['type']);
                $sql .= ")";
            }
        }
        if ($rowNumber>0)
        {
            $command = Yii::app()->db->createCommand(
                 $sql
            );
            $command->query();
        }
    }

    public function getOne($id) {
        try {

        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function getYearRestDays($yearToGet)
    {
        $instanceOfModel = self::model();
        // ���� ��� ������, ������� ��������� � ������ ����, ���������������� �� �����������
        return
            $instanceOfModel->findAllBySql(
                "SELECT * FROM mis.shedule_rest_days srd WHERE
                    SUBSTR(CAST(srd.date AS CHARACTER VARYING),0,5) = '".(intval($yearToGet))."'
                    ORDER BY srd.date
                "
            );


    }

    public function getRows($date, $doctorId) {
        $connection = Yii::app()->db;


    }

    public function getByEnterprise($id) {
        try {

        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}

?>