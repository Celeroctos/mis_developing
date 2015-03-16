<?php
/**
 * Конфиг тестовой базы
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
return array(
	'class'=>'DbConnection',
	'connectionString' => 'pgsql:host=127.0.0.1;port=5432;dbname=postgres;',
	'username' => 'postgres',
	'password' => '12345',
	'defaultSchema'=>'system', //используется исключительно в консоли.
);