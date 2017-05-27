<?PHP
class MainWindowHandler
{
  public function __construct()
  {
    GLOBAL $handlersForToken;
    $handlersForToken['{oo actual_page oo}'][] = array($this, 'handleActualPage');
    $handlersForToken['{oo container_class oo}'][] = array($this, 'handleContainerClass');
    $handlersForToken['{oo main oo}'][] = array($this, 'handleMain');
    $handlersForToken['{oo menu oo}'][] = array($this, 'handleMenu');
    $handlersForToken['{oo imgpath oo}'][] = array($this, 'handleImgPath');
    $handlersForToken['{oo filepath oo}'][] = array($this, 'handleFilePath');
  }

  public function handleContainerClass()
  {
    if(isMobileBrowser()) return 'mobile_container';
    else return 'container';
  }

  public function handleActualPage()
  {
    return '{oo login oo}';
  }

  public function handleMenu()
  {
    return getContent('menu');
  }

  public function handleImgPath()
  {
    GLOBAL $config;
    return $config['domain'].'img';
  }

  public function handleFilePath()
  {
    GLOBAL $config;
    return $config['domain'].'files';
  }

  public function handleMain()
  {
    GLOBAL $post;

    $this->handleRedirects();

    if(isset($post['menuclick']))
      return getContent('page_'.$post['menuclick']);
    else
      return getContent('page_navi');
  }

  private function handleRedirects()
  {
    GLOBAL $post;

    if(isset($post['somevalue']))
    {
      $post['menuclick'] = 'somepage';
    }
  }

};

?>