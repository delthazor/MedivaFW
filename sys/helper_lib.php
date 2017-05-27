<?PHP

function ut($text)
{
  $from = array ("Ô", "Õ", "Û", "Ũ", "ô", "õ", "û", "ũ");
  $to = array ("Ő", "Ő", "Ű", "Ű", "ő", "ő", "ű", "ű");
  $text = mb_convert_encoding($text,"UTF-8");
  $text = str_replace($from, $to, $text);
  return $text;
};

function crypta($text)
{
  GLOBAL $config;
  $text .= $config['salt'];
  return hash('sha256', (base64_encode(md5($text))));
}

function decnum($number, $dec = 0)
{
  return number_format($number, $dec, '.', ' ');
}

function relativePercent($num)
{
  $percent = abs($num-(1.0));
  if ($num > (1.0))
  {
    return '+'.decnum(100*$percent).'%';
  }
  else if ($num < (1.0))
  {
    return '-'.decnum(100*$percent).'%';
  }

  return '+/- 0%';
}

function printbool($bool)
{
  return ($bool ? 'True' : 'False');
}

function validin($input, $limit)
{
  GLOBAL $sqlObject;

  $filter = Array('<script>','</script>', 'onclick', 'onload', '--');

  $accept = isset($input);
  $accept = $accept && strcmp($input, $sqlObject->string_escape($input)) == 0;
  $accept = $accept && strcmp(strtolower($input), str_replace($filter, '', strtolower($input))) == 0;

  if($limit>0)
  {
    $accept = $accept && (strlen($input)<=$limit);
  }

  return $accept;
}

function isMobileBrowser()
{
if(!isset($_SERVER['HTTP_USER_AGENT'])
&& !isset($_SERVER['HTTP_ACCEPT'])
&& !isset($_SERVER['ALL_HTTP'])
) return false;

$userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
if (strpos($userAgent, 'windows') > 0
    && !(strpos($userAgent, 'iemobile') > 0)
   )
{
  return false;
}

if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iemobile|ppc|android)/i', $userAgent))
{
  return true;
}

$httpAccept = strtolower($_SERVER['HTTP_ACCEPT']);
if (strpos($httpAccept, 'application/vnd.wap.xhtml+xml') > 0
    || isset($_SERVER['HTTP_X_WAP_PROFILE'])
    || isset($_SERVER['HTTP_PROFILE'])
    )
{
  return true;
}

$mobile_ua = substr($userAgent, 0, 4);
$mobile_agents = array(
    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
    'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
    'wapr','webc','winw','winw','xda ','xda-');

if (in_array($mobile_ua, $mobile_agents))
{
  return true;
}
$allHttp = strtolower($_SERVER['ALL_HTTP']);
if (strpos($allHttp, 'operamini') > 0)
{
  return true;
}

return false;
}

?>