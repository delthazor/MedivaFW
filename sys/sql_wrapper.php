<?PHP
require_once 'sql_interface.php';

class sqlData
{
  public $querytext;
  public $records;
  public $success;
  public $rowcount;
  public $record_iterator;

  function __construct()
  {
    $this->querytext = "Unknown query";
    $this->success = true;
    $this->rowcount = 0;
    $this->records = Array();
    $this->record_iterator = 0;
  }
};

class sqlObject implements SQL_Wrapper
{
  private $currentData;
  private $dataStack;
  private $errorMsg;
  private $link;

  private function logError($error)
  {
    GLOBAL $config;
    if($config['debug'])
    {
      $this->errorMsg .= "<br>".$error;
    }
    else
    {
      $this->errorMsg = $error;
    }
  }

  private function connect()
  {
    GLOBAL $config;

    if($config['isDbPresent'])
    {
      $this->link = mysqli_connect($config['db_domain'], $config['db_user'], $config['db_password'], $config['db']);
      mysqli_query($this->link, "SET NAMES utf8");
    }
  }

  private function disconnect()
  {
    mysqli_close($this->link);
  }

  private function __construct()
  {
    $this->dataStack = Array();
    $this->currentData = new sqlData();

    $this->connect();
    if(!$this->link)
    {
      $this->errorMsg = "Error in connecting to database.";
      $this->currentData->success = false;
    }
    else
    {
      $this->errorMsg = "No data has been fetched yet.";
      $this->currentData->success = true;
    }

    return $this;
  }

  public function __destruct()
  {
    $this->disconnect();
  }

  public function __wakeup()
  {
    $this->connect();
  }

  public static function getInstance()
  {
    GLOBAL $config;

    static $instance = NULL;
    if($config['isDbPresent'] && is_null($instance))
    {
      $instance = new sqlObject();
    }
    return $instance;
  }

  public static function makeCustomConnection($host, $user, $password, $database)
  {
    return mysqli_connect($host, $user, $password, $database);
  }

  public static function closeCustomConnection($connection)
  {
    return mysqli_close($connection);
  }

  public function switchInstance($customConnection)
  {
    $oldLink = $this->link;
    $this->link = $customConnection;
    return $oldLink;
  }

  public function string_escape($string)
  {
    if(!$this->link)
    {
      logError("String escape failed due to missing database connection.");
      return "";
    }
    else
    {
      return mysqli_real_escape_string($this->link, $string);
    }
  }

  public function last_auto_id()
  {
    if(!$this->link)
    {
      logError("Last auto id failed due to missing database connection.");
      return "";

    }
    else
    {
      return mysqli_insert_id($this->link);
    }
  }

  public function fromQuery($query)
  {
    GLOBAL $config;
    if($config['isDbPresent'])
    {
      $this->currentData->querytext = $query;
      $resource = mysqli_query($this->link, $query);
      $this->fromResource($resource);
    }
    return $this;
  }

  public function fromResource($resource)
  {
    GLOBAL $config;
    if(!$config['isDbPresent'])
    {
      $logmsg .= "No Database configuration";
      return $this;
    }

    $logmsg = $this->currentData->querytext." ";
    $this->currentData->record_iterator = 0;
    $this->currentData->records = Array();
    if($resource === false)
    {
      $this->currentData->success = false;
      $this->currentData->rowcount = 0;
      $logmsg .= mysqli_error($this->link);
    }
    else
    {
      $this->currentData->success = true;
      if($resource === true)
      {
        $this->currentData->rowcount = mysqli_affected_rows($this->link);
      }
      else
      {
        $this->currentData->rowcount = mysqli_num_rows($resource);
        while($row = mysqli_fetch_array($resource))
        {
          $this->currentData->records[] = $row;
        }
      }

      $logmsg .= "Ok";
    }

    $this->logError($logmsg);
    return $this;
  }

  public function getFirstRecord()
  {
    if($this->currentData->success && isset($this->currentData->rowcount) && $this->currentData->rowcount > 0)
    {
     $this->currentData->record_iterator = 1;
     return $this->currentData->records[0];
    }
    else
    {
      return false;
    }
  }

  public function getNextRecord()
  {
    if($this->currentData->record_iterator < $this->currentData->rowcount)
    {
      $returnValue = $this->currentData->records[$this->currentData->record_iterator];
      ++$this->currentData->record_iterator;
    }
    else
    {
      $returnValue = false;
    }
    return $returnValue;
  }

  public function getRowCount()
  {
    return $this->currentData->rowcount;
  }

  public function getErrorMsg()
  {
    return $this->errorMsg;
  }

  public function wasSuccessful()
  {
    return $this->currentData->success;
  }

  public function pushData()
  {
    $this->dataStack[] = new sqlData();
    $top = count($this->dataStack) - 1;
    $this->dataStack[$top]->querytext = $this->currentData->querytext;
    $this->dataStack[$top]->success = $this->currentData->success;
    $this->dataStack[$top]->rowcount = $this->currentData->rowcount;
    $this->dataStack[$top]->record_iterator = $this->currentData->record_iterator;
    $this->dataStack[$top]->records = Array();
    foreach($this->currentData->records as $item)
    {
      $this->dataStack[$top]->records[] = $item;
    }
  }

  public function popData()
  {
    $temp = array_pop($this->dataStack);
    if(!is_null($temp))
    {
      $top = count($this->dataStack) - 1;
      $this->currentData->querytext = $temp->querytext;
      $this->currentData->success = $temp->success;
      $this->currentData->rowcount = $temp->rowcount;
      $this->currentData->record_iterator = $temp->record_iterator;
      $this->currentData->records = Array();
      foreach($temp->records as $item)
      {
        $this->currentData->records[] = $item;
      }
    }
    else
    {
      $this->logError("Invalid pop!");
      $this->currentData->success = false;
    }
  }

  public function getSaveCount()
  {
    return count($this->dataStack);
  }
};
?>