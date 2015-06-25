<?php

class MedcardTemplateEx extends MedcardTemplate {

    public function fetchCategories($id = null, $ar = true) {
        if ($id == null) {
            $id = $this->getAttribute('id');
        }
        $template = $this->getDbConnection()->createCommand()
            ->select('categorie_ids')
            ->from('mis.medcard_templates')
            ->where('id = :id', [
                ':id' => $id
            ])->queryRow();
        if (!$template) {
            throw new CException('Template with ('. $id .') identification number doesn\'t exists');
        } else if ($list = json_decode($template['categorie_ids'])) {
            if ($ar) {
                return MedcardCategoryEx::model()->findAll('id in ('. implode(', ', $list) .')');
            } else {
                return $list;
            }
        } else {
            return [];
        }
    }
}