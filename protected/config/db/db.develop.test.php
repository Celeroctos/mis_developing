<?php
/**
 * Конфиг разработчика для создания на него символической ссылки.
 */

return array(
	'class'=>'DbConnection',
	'connectionString' => 'pgsql:host=127.0.0.1;port=5432;dbname=battle;',
	'username' => 'postgres',
	'password' => '12345',
	'defaultSchema'=>'system', //используется исключительно в консоли.
);
