# Codeigniter 3 library for couchDB database
<hr>
The <strong>couchdb_ci</strong> is a library for codeigniter 3 using couchdb database


## Requirements
Make sur that you have intalled `curl` in your server
 - PHP >= 7.0.0 with `curl` module installed
 - Codeigniter 3.*

## Getting started

 ### 1st step:
 Download or clone this repository with the command
 `git clone https://github.com/Dadapas/couchdb_ci`. Extract file in your codeigniter project.
### 2nd step:
Configure your couchdb configuration server in `application/config/couchdb.php`.
```php
<?php

$config['envirnment'] = array(
  'host'    => 'your-host-name',
  'port'    => port,
  'username'=> 'your-username',
  'password'=> 'your-password'
);
/*
 ...
 You can put a multupl config here

 Don't forget to select witch one you want to use
*/

// Make sur that you selected
$config['db_selected'] = $config['development'];
```
### Use it in your controller

```php
<?php


class MyController extends CI_Controller
{
  public function myMethod()
  {
    // This have another param dbname
    $this->load->library("couchdb", ['dbname' => 'dbname']);

    // Or set it up in setDatabaseName
    $this->couchdb->setDatabaseName("dbname");

    // Get database informations
    $info = $this->couchdb->getDatabaseInfo();

    // Set up data in the
    $this->couchdb->myKey = $value;

    // Get data
    $data = $this->couchdb->myKey;
  }
}
```


## Methods

## Author
Pascal TOVOHERY <tovoherypascal@gmail.com>
## Licence
&copy; 2018 All right reserved, MIT licence
