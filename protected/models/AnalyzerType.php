<?php

class AnalyzerType extends GActiveRecord {

	public function getForm() {
		return new AnalyzerTypeForm();
	}

    public function rules() {
        return [
            ['type_name', 'required'],
            ['type_name, name', 'length', 'max' => 100],
            ['notes, analysis_types', 'safe'],
		];
    }

    public function tableName() {
        return 'lis.analyzer_type';
    }

    public function relations() {
        return [
            'analyzerTypeAnalysis' => [ self::HAS_MANY,
				'AnalyzerTypeAnalysis',
				'analyser_type_id'
			],
            'machines' => [ self::HAS_MANY,
				'Machine',
				'analyzer_type_id'
			],
            'analyserTypeAnalysis'=> [ self::MANY_MANY,
				'AnalysisType',
                'lis.analyzer_type_analysis(analysis_type_id, analyser_type_id)'
			],
		];
    }

	public function findAnalysisTypes($id) {
		$rows = $this->getDbConnection()->createCommand()
			->select("ats.*")
			->from("lis.analyzer_type as at")
			->join("lis.analyzer_type_to_analysis_type as at_at", "at_at.analyzer_type_id = at.id")
			->join("lis.analysis_type as ats", "at_at.analysis_type_id = ats.id")
			->where("at.id = :id", [
				":id" => $id
			])->queryAll();
		return $rows;
	}

	public function saveAnalysisTypes($id, array $ids) {
		$rows = 0;
		foreach ($ids as $i) {
			$rows += $this->getDbConnection()->createCommand()
				->insert("lis.analyzer_type_to_analysis_type", [
					"analyzer_type_id" => $id,
					"analysis_type_id" => $i
				]);
		}
		return $rows;
	}

	public function dropAnalysisTypes($id, array $ids) {
		$rows = 0;
		foreach ($ids as $i) {
			$rows += $this->getDbConnection()->createCommand()
				->delete("lis.analyzer_type_to_analysis_type", "analyzer_type_id = :at and analysis_type_id = :as", [
					":at" => $id,
					":as" => $i
				]);
		}
		return $rows;
	}
}