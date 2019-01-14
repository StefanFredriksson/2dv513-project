<?php
namespace view;

class BaseView {
  private static $register = 'register';
  private static $login = 'login';
  private static $logout = 'logout';
  private static $runescape = 'runescape';
  private static $profile = 'profile';
  private static $users = 'users';
  private $loginView;
  private $registerView;
  private $runescapeView;
  private $profileView;
  private $statView;
  private $userView;

  public function __construct($connection) {
    $this->loginView = new LoginView();
    $this->registerView = new RegisterView();
    $this->runescapeView = new RuneScapeView();
    $this->profileView = new ProfileView($connection);
    $this->statView = new StatView($connection);
    $this->userView = new UserView($connection);
  }

  public function render() {
    $mainBody = $this->getContent();

    echo '
    <!DOCTYPE html>
    <html>
      <head>
      <meta charset="UTF-8">
      <title>Project</title>
      <link href="View/css/style.css" rel="stylesheet">
      </head>
      <body>
      ' . $this->generateBaseView() . '
      ' . $mainBody . '
      </body>
    </html>
    ';
  }

  private function generateBaseView() {
    $logoutBtn = '';
    $profileBtn = '';
    $login = '';
    if (isset($_SESSION["loggedIn"])) {
      $logoutBtn = '<li><form method="post">
        <button type="submit" class="pretty-button" name="' . self::$logout . '">Logout</button>
      </form></li>
      ';
      $profileBtn = '<li><a href="/db_project/index.php?' . self::$profile . '">Profile</a></li>';
    } else {
      $login = '<li><a href="/db_project/index.php?' . self::$login . '">Login</a></li>';
    }
    return '
    <div id="navbar">
      <ul>
        <li><a href="/db_project/index.php">Main Page</a></li>
        <li><a href="/db_project/index.php?' . self::$register . '">Register</a></li>
        ' . $login . '
        <li><a href="/db_project/index.php?' . self::$users . '">Users</a></li>
        ' . $profileBtn . '
        ' . $logoutBtn . '
        <li>
          <form method="get" id="searchbar">
            <input type="hidden" name="' . self::$runescape . '">
            <input type="text" class="text-input" name="name" placeholder="RuneScape username..." required>
            <button type="submit" class="pretty-button" name="searchName">Search</button>
          </form>
        </li>
      </ul>
    </div>
    ';
  }

  private function getContent() {
    if(isset($_GET[self::$register])) {
      return $this->registerView->generateRegisterForm();
    } else if (isset($_GET[self::$runescape])) {
      return $this->runescapeView->generateRuneScapeUser();
    } else if (isset($_GET[self::$login])) {
      return $this->loginView->generateLoginForm();
    } else if (isset($_GET[self::$profile])) {
      return $this->profileView->generateProfilePage();
    } else if (isset($_GET["closed-id"]) || isset($_GET["open-id"])) {
      return $this->statView->generateStatPage();
    } else if (isset($_GET[self::$users])) {
      return $this->userView->generateUsersHTML();
    }

    return '<div class="base-content">
    <h1>This is the homepage!</h1>
    </div>';
  }

  public function wantsToLogout() {
    return isset($_POST[self::$logout]);
  }

  public function redirectToMainPage() {
    header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]);
  }

  public function redirectToProfilePage() {
    header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . '?profile');
  }

  public function redirectToSamePage() {
    header('Location: http://' . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . '?' . $_SERVER["QUERY_STRING"]);
  }
}
?>