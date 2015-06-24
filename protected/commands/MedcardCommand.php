<?php

class MedcardCommand extends CConsoleCommand {

    const CHUNK = 100;

    public function actionMigrate() {
        $delete = $this->confirm('Would you like to remove just migrated rows?');
        $this->migrateTable('mis.medcard_comments', 'medcard.medcard_comment', function($row) {
            return [
                'id' => $row['id'],
                'comment' => $row['comment'],
                'id_medcard' => $row['medcard_id'] != -1 ? $row['medcard_id'] : null,
                'create_date' => $row['creation_date'],
                'employer_id' => $row['doctor_id'] != -1 ? $row['doctor_id'] : null,
            ];
        }, $delete);
        $this->migrateTable('mis.medcard_guides', 'medcard.medcard_guide', [
            'id' => 'id', 'name' => 'name',
        ], $delete);
        $this->migrateTable('mis.medcard_guide_values', 'medcard.medcard_guide_value', function($row) {
            return [
                'id' => $row['id'],
                'guide_id' => $row['guide_id'] != -1 ? $row['guide_id'] : null,
                'value' => $row['value'],
                'greeting_id' => $row['greeting_id'] != -1 ? $row['guide_id'] : null,
            ];
        }, $delete);
        $this->migrateTable('mis.medcard_elements', 'medcard.medcard_element', function($row) {
            $flags = 0;
            if ($row['allow_add']) {
                $flags |= MedcardElementEx::FLAG_GROWABLE;
            }
            if ($row['is_wrapped']) {
                $flags |= MedcardElementEx::FLAG_WRAPPED;
            }
            if ($row['is_required']) {
                $flags |= MedcardElementEx::FLAG_REQUIRED;
            }
            if ($row['not_printing_values']) {
                $flags |= MedcardElementEx::FLAG_NOT_PRINT_VALUES;
            }
            if ($row['hide_label_before']) {
                $flags |= MedcardElementEx::FLAG_HIDE_LABEL_BEFORE;
            }
            return [
                'id' => $row['id'],
                'type' => $row['type'],
                'category_id' => $row['categorie_id'] != -1 ? $row['categorie_id'] : null,
                'label_before' => $row['label_before'],
                'label_after' => $row['label_after'],
                'label_display' => $row['label_display'],
                'guide_id' => $row['guide_id'],
                'default_value' => $row['default_value'],
                'size' => $row['size'],
                'flags' => $flags,
                'position' => $row['position'],
                'config' => $row['config'],
            ];
        }, $delete);
        $this->migrateTable('mis.medcard_categories', 'medcard.medcard_element', function($row) {
            $flags = 0;
            if ($row['allow_add']) {
                $flags |= MedcardElementEx::FLAG_GROWABLE;
            }
            if ($row['is_wrapped']) {
                $flags |= MedcardElementEx::FLAG_WRAPPED;
            }
            if ($row['is_required']) {
                $flags |= MedcardElementEx::FLAG_REQUIRED;
            }
            if ($row['not_printing_values']) {
                $flags |= MedcardElementEx::FLAG_NOT_PRINT_VALUES;
            }
            if ($row['hide_label_before']) {
                $flags |= MedcardElementEx::FLAG_HIDE_LABEL_BEFORE;
            }
            return [
                'id' => $row['id'],
                'type' => null,
                'category_id' => $row['parent_id'] != -1 ? $row['parent_id'] : null,
                'label_before' => null,
                'label_after' => null,
                'label_display' => null,
                'guide_id' => null,
                'default_value' => null,
                'size' => null,
                'flags' => $flags | MedcardElementEx::FLAG_CATEGORY,
                'position' => $row['position'],
                'config' => null,
            ];
        }, $delete);
        $this->migrateTable('mis.medcard_templates', 'medcard.medcard_template', function($row) {
            if (!$categories = json_decode($row['categorie_ids'])) {
                $categories = [];
            }
            foreach ($categories as $c) {
                Yii::app()->getDb()->createCommand()->insert('medcard.medcard_template_to_element', [
                    'template_id' => $row['id'], 'category_id' => $c
                ]);
            }
            return [
                'id' => $row['id'],
                'name' => $row['name'],
                'primary_diagnosis' => $row['primary_diagnosis'],
                'page_id' => $row['page_id'],
                'index' => $row['index'],
            ];
        }, $delete);
    }

    private function countItems($table) {
        $count = Yii::app()->getDb()->createCommand()->select('count(1) as count')
            ->from($table)->queryRow();
        if ($count != null) {
            return $count['count'];
        } else {
            return 0;
        }
    }

    private function migrateTable($from, $to, $structure, $delete = true, $safe = true) {
        if ($safe) {
            $transaction = Yii::app()->getDb()->beginTransaction();
        } else {
            $transaction = null;
        }
        try {
            if ($delete) {
                Yii::app()->getDb()->createCommand()->delete($to);
            }
            print 'Table migration from "'. $from .'" to "'. $to .'" ... ';
            $time = time();
            $pager = new CPagination($this->countItems($to));
            $pager->setPageSize(static::CHUNK);
            for ($i = 0; $i < $pager->getPageCount(); $i++) {
                $pager->setCurrentPage($i);
                $rows = Yii::app()->getDb()->createCommand()
                    ->select('*')
                    ->from($from)
                    ->limit($pager->getLimit(), $pager->getOffset())
                    ->queryAll();
                foreach ($rows as $row) {
                    if (is_callable($structure)) {
                        $insert = call_user_func($structure, [
                            $row, $from, $to,
                        ]);
                    } else if (is_array($structure)) {
                        $insert = [];
                        foreach ($structure as $f => $t) {
                            $insert[$t] = $row[$f];
                        }
                    } else {
                        throw new CException('Migration structure should be closure or array');
                    }
                    Yii::app()->getDb()->createCommand()->insert($to, $insert);
                }
            }
            if ($transaction != null) {
                $transaction->commit();
            }
            print 'Finished in '.(time() - $time).' seconds'."\r\n";
        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }
}