<?php
/**
 * Конфиг разработчика для создания на него символической ссылки.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */
return array(
	'class'=>'DbConnection',
	'connectionString' => 'pgsql:host=10.1.4.8;port=5432;dbname=postgres;',
	'username' => 'moniiag',
	'password' => '12345',
	'defaultSchema'=>'system', //используется исключительно в консоли.
);
