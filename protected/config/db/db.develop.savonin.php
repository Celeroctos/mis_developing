<?php
/**
 * Конфиг разработчика для создания на него символической ссылки.
 */

return array(
	'class'=>'DbConnection',
	'connectionString' => 'pgsql:host=localhost;port=5432;dbname=moniiag;',
	'username' => 'postgres',
	'password' => '12345',
	'defaultSchema'=>'public',
);
