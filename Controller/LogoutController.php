<?php
namespace controller;

class LogoutController extends BaseController {
  public function __construct($connection) {
    parent::__construct($connection);
  }

  public function handleLogout() {
    unset($_SESSION["loggedIn"]);
    unset($_SESSION["username"]);
    $this->baseView->redirectToMainPage();
  }
}
?>