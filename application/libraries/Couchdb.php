<?php

/**
 * Couchdb class
 * @see http://docs.couchdb.org/en/stable/index.html
**/
class Couchdb
{
  /**
   * @property Codeigniter instance
  **/
  private $CI;

  private $curl;

  private $config;

  private $url;

  protected $dbname;

  public function __construct(string $dbname = '')
  {
    $this->CI = & get_instance();

    $this->dbname = $dbname;

    $this->CI->config->load("couchdb");

    $this->config = $this->CI->config->item("db_selected");

    $this->url = 'http://'. $this->config['host'] . ':' . $this->config['port'];

    if ( ! function_exists("curl_init") )
    {

      throw new CouchDBException("Couchdb library need curl to be installed.", 4);

    } else {

      $this->curl = curl_init();
    }

  }

  public function __set(string $key, array $value)
  {
    if ($this->dbname === '')
    {

      throw new CouchDBException("Error processing request, setup database name before ", 1);
    } else {

      $resp = $this->curl_put("/$this->dbname/$key", $value);
      return json_encode($resp);
    }
  }

  /**
   * Magic method get
   * @method __get
  **/
  public function __get(string $key)
  {
    if ($this->dbname === '')
    {

      throw new CouchDBException("Error processing request, setup database name before ", 1);
    } else {

      $resp = $this->curl_get("/$this->dbname/$key");
      return json_encode($resp);
    }
  }

  /**
   * @return bool
   * @method createDatabase
   * @param $dbname
  **/
  public function createDatabase(string $dbname)
  {
    $response  = $this->curl_put("/$dbname");
    $response = json_decode($response);

    if ( ! isset($response->ok))
    {
      throw new CouchDBException("Error processing request, Database exist", 1);
    }

    return true;
  }

  /**
   * @method setDatabaseName
  **/
  public function setDatabaseName(string $dbname)
  {
    $this->dbname = $dbname;
  }

  /**
   * @method removeDatabase
  **/
  public function removeDatabase(string $name)
  {
    $response  = $this->curl_get("/$name");
    $response = json_decode($response);

    if ( ! isset($response->ok))
      throw new CouchDBException("Error processing request, Database not exist", 1);
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
   * @param $dbname
  **/
  public function getDatabaseInfo(string $dbname = $this->dbname)
  {
    $response  = $this->curl_get("/$dbname");
    return json_decode($response);
  }

  private function curl_get(string $url)
  {
    curl_setopt($this->curl, CURLOPT_URL, $this->url . $url);
    curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

    return curl_exec($this->curl);
  }

  private function curl_put(string $url, array $data = [])
  {
    $data_json = json_encode($data);

    curl_setopt($this->curl, CURLOPT_URL, $this->url . $url );
    curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
    curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PUT');

    /** if there is data passing as param **/
    if (count($data) >= 1)
      curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data_json);

    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

    /** return response **/
    return curl_exec($this->curl);
  }

  /**
   * @method post
   * @param $url
   * @param $data
  **/
  private function curl_post(string $url, array $data = [])
  {
    $data_json = json_encode($data);

    curl_setopt($this->curl, CURLOPT_URL, $this->url . $url );
    curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($this->curl, CURLOPT_POST, 1);

    /** if there is param **/
    if (count($data) >= 1)
      curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data_json);

    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

    /** return response **/
    return curl_exec($this->curl);
  }

  /**
   * @param $url
   * @param $params
  */
  private function curl_delete(string $url, array $params = [])
  {
    $data_json = json_encode($params);
    curl_setopt($this->curl, CURLOPT_URL, $this->url . $url);
    curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "DELETE");

    if (count($params) >= 1)
      curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data_json);

    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

    /** return response **/
    return curl_exec($this->curl);
  }

  public function getUUID()
  {
    $resp = $this->curl_get("/_uuids");
    return json_encode($resp);
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
