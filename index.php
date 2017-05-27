<?PHP
require_once 'config/master_config.php';
require_once 'sys/main_lib.php';

session_start();

if($config['debug'])
{
print_r($_POST); echo '<br><br>';
print_r($_SESSION['user']); echo '<br><br>';
}

$get = $_GET;
$post = $_POST;
unset($_GET); unset($_POST);

$isAdminPage = false;
if (isset($get['admin']))
{
  $isAdminPage = true;
}

echo constructContent();
?>