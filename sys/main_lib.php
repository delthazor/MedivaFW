<?PHP

$bodyContent = '';
require_once 'sys/globals.php';

require $config['root'].'handlers/tpl_handler.php';
require_once $config['root'].'sys/helper_lib.php';
require_once $config['root'].'custom/customIncludes.php';

function getContent($content)
{
  GLOBAL $config;
  $fpath = $config['root'].'content/'.$content.'.html';
  if(file_exists($fpath))
  {
    if($config['debug'])
    {
      echo '<br>Loaed: '.$content.'<br>';
    }
    return (file_get_contents($fpath));
  }
  else
  {
    if($config['debug'])
    {
      echo '<br>Error in loading<br>';
    }
    return file_get_contents($config['root'].'content/unexpected_error.html');
  }
}

function displayErrors()
{
  GLOBAL $bodyContent;
  $errorlist = '<h1>HIBA</h1>';
  for($i = 0; $i<count($_SESSION['errorlist']); ++$i)
  {
    $errorlist .= $_SESSION['errorlist'][$i].'<br>';
  }
  $_SESSION['errorlist'] = Array();
  $bodyContent = str_replace(Array('display: none;', '--list of errors--'), Array('display: auto;', $errorlist), $bodyContent);
}

function evalTmpl()
{
  GLOBAL $config;
  GLOBAL $bodyContent;
  GLOBAL $get;
  GLOBAL $post;

  $lastpos = 0;
  while($lastpos !== false)
  {
    $p1 = strpos($bodyContent, '{oo', $lastpos);
    if ($p1 !== false)
    {
      $p2 = strpos($bodyContent, 'oo}', $p1);
      $tmpl = substr($bodyContent, $p1, $p2-$p1+3);
      $newcontent = handleToken($tmpl);
      $bodyContent = str_replace($tmpl, $newcontent, $bodyContent);
      $lastpos = strpos($bodyContent, '{oo ', $p1-1);
    }
    else
    {
      $lastpos = false;
    }
  }
}

function constructContent()
{
  GLOBAL $bodyContent;
  GLOBAL $config;
  GLOBAL $isAdminPage;

  $_SESSION['errorlist'] = Array();
  initGlobals();

  if($isAdminPage)
  {
    $bodyContent .= getContent('adminBase');
  }
  else
  {
    $bodyContent .= getContent('base');
  }

  include_once 'handlers/settings_handler.php';
  evalTmpl();

  if($config['debug'] && isset($_SESSION['user']))
  {
    GLOBAL $sqlObject;
    echo '<br>'.$sqlObject->getErrorMsg();
  }

  if(count($_SESSION['errorlist']) > 0)
  {
    displayErrors();
  }

  return $bodyContent;
}

?>