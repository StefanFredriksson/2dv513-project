<?php
namespace view;

class LoginView {
  private static $username = 'username';
  private static $password = 'password';
  private static $submit = 'submit';

  public function generateLoginForm() {
    return '
    <div class="base-content">
      <h1 class="flash-message">' . $_SESSION["flash"] . '</h1>
      <form method="post">
        <input type="text" class="username-input text-input" name="' . self::$username . '" placeholder="Username..." required><br>
        <input type="password" class="text-input" name="' . self::$password . '" placeholder="Password..." required><br>
        <button type="submit" class="pretty-button" name="' . self::$submit . '">Login</button>
      </form>
    </div>
    ';
  }

  public function wantsToLogin() {
    return isset($_POST[self::$submit]);
  }

  public function getUsername() {
    return $_POST[self::$username];
  }

  public function getPassword() {
    return $_POST[self::$password];
  }
}
?>