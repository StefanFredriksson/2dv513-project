<?php
namespace controller;

class RegisterController extends BaseController {
  public function __construct($connection) {
    parent::__construct($connection);
  }

  public function registerUser() {
    $username = $this->registerView->getUsername();
    $password = $this->registerView->getPassword();
    $result = $this->usersSQL->getUser($username);

    if ($result->num_rows == 0) {
      $this->usersSQL->addUser($username, password_hash($password, PASSWORD_BCRYPT));
      $this->baseView->redirectToMainPage();
    } else {
      $_SESSION["flash"] = "Username already exists.";
      $this->baseView->redirectToSamePage();
    }
  }
}
?>