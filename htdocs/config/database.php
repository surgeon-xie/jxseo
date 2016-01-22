<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

$active_group	= 'default';
$query_builder	= TRUE;

$db['default']	= array(
	'dsn'		=> '',
	'hostname'	=> 'qdm12192597.my3w.com',
	'username'	=> 'qdm12192597',
	'password'	=> 'Q19891016311q',
	'port'		=> '3306',
	'database'	=> 'qdm12192597_db',
	'dbdriver'	=> 'mysqli',
	'dbprefix'	=> 'dr_',
	'pconnect'	=> FALSE,
	'db_debug'	=> TRUE,
	'cache_on'	=> FALSE,
	'cachedir'	=> 'cache/sql/',
	'char_set'	=> 'utf8',
	'dbcollat'	=> 'utf8_general_ci',
	'swap_pre'	=> '',
	'autoinit'	=> FALSE,
	'encrypt'	=> FALSE,
	'compress'	=> FALSE,
	'stricton'	=> FALSE,
	'failover'	=> array(),
);
