<?php


/**
 * Class description
**/
class Couchdb
{
  private $CI;

  private $curl;

  private $config;

  private $url;

  protected $couch;

  public function __construct()
  {
    $this->CI = & get_instance();

    $this->CI->config->load("couchdb");

    $this->config = $this->CI->config->item("db_selected");

    $this->url = 'http://'. $this->config['host'] . ':' . $this->config['port'];

    if ( ! function_exists("curl_init") )
    {

      throw new CouchDBException("Couchdb library need curl installed.", 1);
      exit;
    } else {

      $this->curl = curl_init();
    }

  }

  /**
   * @method createDatabase
  **/
  public function createDatabase(string $name)
  {

    curl_setopt($this->curl, CURLOPT_URL, $this->url .'/'. $name);
    curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
    $response  = curl_exec($this->curl);
    $response = json_decode($response);

    if ( ! isset($response->ok))
    {
      throw new CouchDBException("Error Processing Request, Database exist", 1);
    }

    return true;
  }

  /**
   * @method removeDatabase
  **/
  public function removeDatabase(string $name)
  {
    curl_setopt($this->curl, CURLOPT_URL, $this->url .'/'. $name);
    curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
    $response  = curl_exec($this->curl);
    $response = json_decode($response);

    if ( ! isset($response->ok))
      throw new CouchDBException("Error Processing Request, Database not exist", 1);
    else
      return true;
  }

  /**
   *
   * compact_running
   * doc_count
   * db_name
   * purge_seq
   * committed_update_seq
   * doc_del_count
   * disk_format_version
   * update_seq
   * instance_start_time
   * disk_size
   *
   * @method getDatabaseInfo
   * @return stdClass
  **/
  public function getDatabaseInfo(string $name)
  {
    curl_setopt($this->curl, CURLOPT_URL, $this->url .'/'. $name);
    curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'GET');

    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
    $response  = curl_exec($this->curl);
    return json_decode($response);
  }

  public function __set(string $key, mixed $value)
  {

  }

  /**
   * @method set
  **/
  public function set(string $json)
  {
    curl_setopt($this->curl, CURLOPT_URL, $this->url .'/'. $name);
    curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
    $response  = curl_exec($this->curl);
    $response = json_decode($response);
  }

  private function post(array $post)
  {

  }

  private function get(string $url = '')
  {
    
  }

  /***
   * Close curl
  **/
  public function __destruct()
  {
    curl_close($this->curl);
  }
}


/**
 * CouchDBException
**/
class CouchDBException extends Exception {}
