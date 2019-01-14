<?php
namespace controller;

class MasterController extends BaseController {
  private $registerController;
  private $loginController;
  private $logoutController;
  private $runescapeController;
  private $statController;

  public function __construct() {
    $connection = new \mysqli('localhost:3306', 'root', '', 'db_project');
    parent::__construct($connection);
    $this->registerController = new RegisterController($connection);
    $this->loginController = new LoginController($connection);
    $this->logoutController = new LogoutController($connection);
    $this->runescapeController = new RuneScapeController($connection);
    $this->statController = new StatController($connection);
  }

  public function __destruct() {
    $this->connection->close();
  }

  public function start() {
    $this->checkForPostCalls();
    $this->baseView->render();
    $this->emptyFlashIfNoAction();
  }

  private function checkForPostCalls() {
    if ($this->registerView->wantToRegister()) {
      $this->registerController->registerUser();
    } else if ($this->runescapeView->wantsToTrack()) {
      $this->runescapeController->handleTracking();
    } else if ($this->loginView->wantsToLogin()) {
      $this->loginController->handleLogin();
    } else if ($this->baseView->wantsToLogout()) {
      $this->logoutController->handleLogout();
    } else if ($this->statView->wantsToStopTracking()) {
      $this->statController->stopTracking();
    }
  }

  private function emptyFlashIfNoAction() {
    if (count($_POST) == 0) {
      $_SESSION["flash"] = "";
    }
  }
}
?>