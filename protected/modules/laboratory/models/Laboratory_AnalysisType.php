<?php

class Laboratory_AnalysisType extends ActiveRecord {

	public function findWithParametersAndSamples($pk) {
		if (!$row = $this->findByPk($pk)) {
			throw new CException('Can\'t resolve analysis type with identification number "'. $pk .'"');
		}
		return [
			'parameters' => $this->getDbConnection()->createCommand()
				->select('atp.*')
				->from('lis.analysis_type_parameter as atp')
				->join('lis.analysis_type_to_analysis_type_parameter as at_atp', 'at_atp.analysis_type_parameter = atp.id')
				->where('at_atp.analysis_type = :analysis_type_id', [
					':analysis_type_id' => $row->{'id'}
				])->queryAll(),
			'samples' => $this->getDbConnection()->createCommand()
				->select('st.*')
				->from('lis.sample_type_tree as st')
				->join('lis.analysis_type_to_sample_type as at_st', 'at_st.sample_type_id = st.id')
				->where('at_st.analysis_type_id = :analysis_type_id', [
					':analysis_type_id' => $row->{'id'}
				])->queryAll(),
			'analysis' => $row
		];
	}

	public function tableName() {
		return 'lis.analysis_type';
	}
}