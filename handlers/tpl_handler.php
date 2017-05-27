<?PHP

$handlersForToken = Array();

include 'handlers/templates/mainwindow_handler.php';
include 'handlers/templates/login_handler.php';

$hanlderInstances = Array();
$handlerInstances[] = new MainWindowHandler();
$handlerInstances[] = new LoginHandler();

function handleToken($token)
{
  GLOBAL $handlersForToken;

  if(!isset($handlersForToken[$token]) || count($handlersForToken[$token]) == 0)
  {
    $t = str_replace(Array('{oo ', ' oo}'), Array('', ''), $token);
    return '<br>! Template Argument "'.$t.'" Not Found !<br>';
  }
  else
  {
    $newcontent = '';
    foreach($handlersForToken[$token] as $handler)
    {
      $newcontent .= $handler();
    }

    return $newcontent;
  }
}

?>