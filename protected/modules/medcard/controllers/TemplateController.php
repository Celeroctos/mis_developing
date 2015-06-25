<?php

class TemplateController extends ControllerEx {

	public function actionTest() {
		return $this->render('test');
	}

    public function actionDescribe($id) {
        $result = [];
        $elements = MedcardElementPatientEx::model()->fetchByTemplate($id);
        foreach ($elements as $element) {
            if (empty($element['value'])) {
                continue;
            }
            if (!$row = MedcardElementEx::model()->findByAttributes([
                'id' => $element['element_id']
            ])) {
                continue;
            }
            $result[MedcardHtml::createHash($row, '', 'f')] = $element['value'];
        }
        $this->leave([
            'values' => $result
        ]);
    }
}