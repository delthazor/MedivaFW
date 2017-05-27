<?PHP

$config = Array();

$config['domain'] = 'http://localhost/projectname/';

$config['root'] = $_SERVER['DOCUMENT_ROOT'].'/projectname/';

$config['debug'] = false;

$config['salt'] = 'theAnswerIs42';

$config['maxPwLenght'] = 20;

require_once 'db_config.php';

?>