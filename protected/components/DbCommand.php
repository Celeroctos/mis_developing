<?php

class DbCommand extends CDbCommand {

	/**
	 * @var array - Array for WITH clause
	 */
	public $with = null;

	/**
	 * Create new database command with query parameters
	 * @param array|null $query - Array with query parameters
	 * @return CDbCommand
	 */
	public static function createCommand($query = null) {
		return new DbCommand(Yii::app()->getDb(), $query);
	}

	/**
	 * Extension for classic CDbCommand class, which providers
	 * sql's WITH clause
	 * @param string $name - Name of statement
	 * @param string|CDbCommand $command
	 */
	public function with($name, $command) {
		if (is_object($command) && $command instanceof CDbCommand) {
			$this->with[$name] = $command->getText();
		} else {
			$this->with[$name] = $command;
		}
	}

	/**
	 * Builds a SQL SELECT statement from the given query specification.
	 * @param array $query the query specification in name-value pairs. The following
	 * query options are supported: {@link select}, {@link distinct}, {@link from},
	 * {@link where}, {@link join}, {@link group}, {@link having}, {@link order},
	 * {@link limit}, {@link offset} and {@link union}.
	 * @throws CDbException if "from" key is not present in given query parameter
	 * @return string the SQL statement
	 * @since 1.1.6
	 */
	public function buildQuery($query) {
		$sql = "";
		if (!empty($this->with)) {
			$sql = "WITH ";
			foreach ($this->with as $key => $q) {
				if (is_object($q) && $q instanceof CDbCommand) {
					$q = $q->getText();
				}
				$sql .= $key ." AS (". $q ."), ";
			}
			$sql = preg_replace('/,\s$/', " ", $sql);
		}
		$sql .= !empty($query['distinct']) ? 'SELECT DISTINCT' : 'SELECT';
		$sql .= ' '.(!empty($query['select']) ? $query['select'] : '*');
		if(!empty($query['from'])) {
			$sql.="\nFROM ".$query['from'];
		} else {
			throw new CDbException(Yii::t('yii','The DB query must contain the "from" portion.'));
		}
		if(!empty($query['join'])){
			$sql.="\n".(is_array($query['join']) ? implode("\n",$query['join']) : $query['join']);
		}
		if(!empty($query['where'])) {
			$sql.="\nWHERE ".$query['where'];
		}
		if(!empty($query['group'])) {
			$sql.="\nGROUP BY ".$query['group'];
		}
		if(!empty($query['having'])) {
			$sql.="\nHAVING ".$query['having'];
		}
		if(!empty($query['union'])) {
			$sql.="\nUNION (\n".(is_array($query['union']) ? implode("\n) UNION (\n",$query['union']) : $query['union']) . ')';
		}
		if(!empty($query['order'])) {
			$sql.="\nORDER BY ".$query['order'];
		}
		$limit = isset($query['limit']) ? (int)$query['limit'] : -1;
		$offset = isset($query['offset']) ? (int)$query['offset'] : -1;
		if($limit >= 0 || $offset > 0) {
			$sql = $this->getConnection()->getCommandBuilder()->applyLimit($sql, $limit, $offset);
		}
		return $sql;
	}
}