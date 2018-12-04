<?php

/**
 * CouchDB configuration
 *
**/
$config['development'] = array(
  'host'    => '127.0.0.1',
  'port'    => 5984,
  'dbname'  => 'bdde_cara',
  'username'=> '',
  'password'=> '',
);

$config['production'] = array(
  'host'    => '127.0.0.1',
  'port'    => 5984,
  'dbname'  => 'bdde_cara',
  'username'=> '',
  'password'=> '',
);

$config['db_selected'] = $config[ENVIRONMENT];
