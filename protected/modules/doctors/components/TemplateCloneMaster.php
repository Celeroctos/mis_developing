<?php

class TemplateCloneMaster {

    public function __construct($medcardId, $greetingId, $templateId) {
        if (!$this->template = MedcardTemplateEx::model()->findByPk($templateId)) {
            throw new CHttpException('Шаблон с таким идентификатором ('. $templateId .') не существует');
        } else {
            $this->recordId = MedcardElementForPatient::getMaxRecordId($medcardId);
        }
        $this->medcardId = $medcardId;
        $this->greetingId = $greetingId;
        $this->templateId = $templateId;
        $record = new MedcardRecord();
        $user = User::model()->find('id=:id', array(':id' => Yii::app()->{'user'}->id));
        $record->setAttributes([
            'doctor_id' => $user['employee_id'],
            'medcard_id' => $this->medcardId,
            'greeting_id' => $this->greetingId,
            'template_id' => $this->templateId,
            'template_name' => $this->template->{'name'},
            'record_id' => $this->recordId + 1,
            'record_date' => date('Y-m-d H:i'),
        ], false);
        $user->save();
        $this->recordId = $user->{'id'};
    }

    public function cloneTemplateCategory($categoryId) {
        $first = Yii::app()->getDb()->createCommand()
            ->select('count(*) as count')
            ->from('mis.medcard_elements_patient')
            ->where('greeting_id = :greeting_id and template_id = :template_id and element_id = -1 and real_categorie_id = :category_id', [
                ':greeting_id' => $this->greetingId,
                ':template_id' => $this->templateId,
                ':category_id' => $categoryId,
            ])->queryRow();
        if ($first != null && $first['count'] != 0) {
            return ;
        }
        if (!$previous = MedcardCategoryEx::model()->findByAttributes([ 'id' => $categoryId ])) {
            throw new CHttpException('Категория с таким идентификатором ('. $categoryId .') не существует');
        }
        $category = new MedcardElementPatientEx();
        $category->setAttributes([
            'medcard_id' => $this->medcardId,
            'element_id' => -1,
            'value' => null,
            'history_id' => 1,
            'change_date' =>  date('Y-m-d H:i'),
            'greeting_id' => $this->greetingId,
            'categorie_name' => $previous->{'name'},
            'path' => $previous->{'path'},
            'label_before' => null,
            'label_after' => null,
            'size' => null,
            'is_wrapped' => $previous->{'is_wrapped'},
            'categorie_id' => $previous->{'parent_id'},
            'type' => -1,
            'template_id' => $this->templateId,
            'template_name' => null,
            'guide_id' => null,
            'is_dynamic' => $previous->{'is_dynamic'},
            'real_categorie_id' => $previous->{'id'},
            'allow_add' => 0,
            'config' => json_encode([
                'isWrapped' => $previous->{'is_wrapped'}
            ]),
            'is_required' => 0,
            'is_record' => 0,
            'record_id' => $this->recordId,
            'template_page_id' => $this->template->{'page_id'},
            'not_printing_values' => 0,
            'hide_label_before' => 0,
        ], false);
        try {
            if (!$category->save(false)) {
                throw new CException('Can\'t save history row in database');
            }
        } catch (\Exception $e) {
        }
        if ($previous->{'parent_id'} > 0) {
            $this->cloneTemplateCategory($previous->{'parent_id'});
        }
        $rows = Yii::app()->getDb()->createCommand()
            ->select('id')
            ->from('mis.medcard_elements')
            ->where('categorie_id = :category_id', [
                'category_id' => $categoryId
            ])->queryAll();
        foreach ($rows as $row) {
            $this->cloneTemplateElement($row['id']);
        }
    }

    public function cloneTemplateElement($elementId, $value = null, $path = null) {
        if (!$previous = MedcardElementEx::model()->findByPk($elementId)) {
            throw new CException('Element with that identification number does not exists ('. $elementId .')');
        } else if ($path == null) {
            $path = $previous->{'path'};
        }
        if (!$config = json_decode($previous->{'config'}, true)) {
            $config = [];
        }
        if (is_array($value)) {
            $value = json_encode($value);
        }
        $element = new MedcardElementPatientEx();
        $element->setAttributes([
            'medcard_id' => $this->medcardId,
            'element_id' => $elementId,
            'value' => $value,
            'history_id' => 1,
            'change_date' => date('Y-m-d H:i'),
            'greeting_id' => $this->greetingId,
            'categorie_name' => null,
            'path' => $path,
            'label_before' => $previous->{'label'},
            'label_after' => $previous->{'label_after'},
            'size' => $previous->{'size'},
            'is_wrapped' => $previous->{'is_wrapped'},
            'categorie_id' => $previous->{'categorie_id'},
            'type' => $previous->{'type'},
            'template_id' => $this->templateId,
            'template_name' => null,
            'guide_id' => $previous->{'guide_id'},
            'is_dynamic' => isset($config['isDynamic']) ? $config['isDynamic'] : 0,
            'real_categorie_id' => null,
            'allow_add' => $previous->{'allow_add'},
            'config' => $previous->{'config'},
            'is_required' => $previous->{'is_required'},
            'is_record' => false,
            'record_id' => $this->recordId,
            'template_page_id' => $this->template->{'page_id'},
            'not_printing_values' => $previous->{'not_printing_values'},
            'hide_label_before' => $previous->{'hide_label_before'},
        ], false);
        try {
            if (!$element->save(false)) {
                throw new CException('Can\'t save history row in database');
            }
        } catch (\Exception $e) {
        }
        if ($previous->{'categorie_id'} > 0) {
            $this->cloneTemplateCategory($previous->{'categorie_id'});
        }
    }

    public function cloneTemplateElements($form) {
        $first = Yii::app()->getDb()->createCommand()
            ->select('count(*) as count')
            ->from('mis.medcard_elements_patient')
            ->where('greeting_id = :greeting_id and template_id = :template_id', [
                ':greeting_id' => $this->greetingId,
                ':template_id' => $this->templateId,
            ])->queryRow();
        if ($first != null && $first['count'] != 0) {
            return ;
        }
        $skip = [ 'medcardId', 'greetingId', 'templateName', 'templateId' ];
        foreach($form as $field => $value) {
            if (in_array($field, $skip) || !preg_match('/^f(\d+\|)*\d+_(\d+)$/', $field)) {
                continue;
            }
            $center = strrpos($field, '_');
            $path = implode('.', explode('|', substr($field, 1, $center - 1)));
            $elementId = substr($field, $center + 1);
            $this->cloneTemplateElement($elementId, $value, $path);
        }
    }

    private $greetingId;
    private $medcardId;
    private $templateId;
    private $template;
    private $recordId;
}