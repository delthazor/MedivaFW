<?PHP

interface SQL_Wrapper
{
  public static function getInstance();
  public static function makeCustomConnection($host, $user, $password, $database);
  public static function closeCustomConnection($connection);
  public function switchInstance($customConnection);

  public function string_escape($string);
  public function last_auto_id();
  public function fromQuery($query); //return $this; !!!
  public function fromResource($resource); //return $this; !!!
  public function getFirstRecord();
  public function getNextRecord();
  public function getRowCount();
  public function getErrorMsg();
  public function wasSuccessful();
  public function pushData();
  public function popData();
  public function getSaveCount();
}

?>