<?php
session_start();

require_once('View/BaseView.php');
require_once('View/RegisterView.php');
require_once('View/LoginView.php');
require_once('View/RuneScapeView.php');
require_once('View/ProfileView.php');
require_once('View/StatView.php');
require_once('View/UserView.php');
require_once('Controller/BaseController.php');
require_once('Controller/MasterController.php');
require_once('Controller/RegisterController.php');
require_once('Controller/LoginController.php');
require_once('Controller/LogoutController.php');
require_once('Controller/RuneScapeController.php');
require_once('Controller/StatController.php');
require_once('Model/UsersSQL.php');
require_once('Model/RunescapeSQL.php');
require_once('Model/RuneScape.php');
require_once('Model/Skill.php');

if (!isset($_SESSION["flash"])) {
  $_SESSION["flash"] = "";
}

$master = new \controller\MasterController();
$master->start();
?>