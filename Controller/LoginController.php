<?php
namespace controller;

class LoginController extends BaseController {
  public function __construct($connection) {
    parent::__construct($connection);
  }

  public function handleLogin() {
    $username = $this->loginView->getUsername();
    $password = $this->loginView->getPassword();
    $result = $this->usersSQL->getUser($username);

    if ($result->num_rows == 1) {
      $result = $result->fetch_assoc();

      if ($this->validatePassword($password, $result["password"])) {
        $_SESSION["loggedIn"] = true;
        $_SESSION["username"] = $username;
        $this->baseView->redirectToProfilePage();
      } else {
        $this->invalidLogin();
      }
    } else {
      $this->invalidLogin();
    }
  }

  private function invalidLogin() {
    $_SESSION["flash"] .= "Wrong username or password";
    $this->baseView->redirectToSamePage();
  }

  private function validatePassword($password, $hashedPassword) {
    return password_verify($password, $hashedPassword);
  }
}
?>