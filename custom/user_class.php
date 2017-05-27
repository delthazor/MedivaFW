<?PHP
class User
{
  public $id;
 public function __construct($id_param)
  {
    GLOBAL $sqlObject;
    $this->id = $id_param;
  }
};
?>