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
}