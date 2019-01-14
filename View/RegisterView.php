<?php
namespace view;

class RegisterView {
  private static $username = 'Username';
  private static $password = 'Password';
  private static $register = 'Register';

  public function __construct() {

  }

  public function generateRegisterForm() {
    return '
    <div class="base-content">
      <h1 class="flash-message">' . $_SESSION["flash"] . '</h1>
      <form method="post">
      <input type="text" class="username-input text-input" name="' . self::$username . '" placeholder="Username..." required><br>
      <input type="password" class="text-input" name="' . self::$password . '" placeholder="Password..." required><br>
      <button type="submit" class="pretty-button" name="' . self::$register . '">Register</button><br>
      </form>
    </div>
    ';
  }

  public function wantToRegister() {
    return isset($_POST[self::$register]);
  }

  public function getUsername() {
    return $_POST[self::$username];
  }

  public function getPassword() {
    return $_POST[self::$password];
  }
}
?>