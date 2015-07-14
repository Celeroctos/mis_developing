<?php
/**
 * ������ ������������ ��� �������� �� ���� ������������� ������.
 * @author Dzhamal Tayibov <prohps@yandex.ru>
 */ 

return array(
	'class'=>'DbConnection',
	'connectionString' => 'pgsql:host=localhost;port=5432;dbname=prod;',
	'username' => 'postgres',
	'password' => '12345',
	'defaultSchema'=>'system', //������������ ������������� � �������.
);