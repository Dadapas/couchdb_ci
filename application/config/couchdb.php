<?php

/**
 * CouchDB configuration
 *
**/
$config['development'] = array(
  'host'    => '127.0.0.1',
  'port'    => 5984,,
  'username'=> '',
  'password'=> '',
);

$config['production'] = array(
  'host'    => '127.0.0.1',
  'port'    => 5984,
  'username'=> '',
  'password'=> '',
);

$config['db_selected'] = $config['development'];
