<?php
namespace view;

class UserView {
  private $usersSQL;

  public function __construct($connection) {
    $this->usersSQL = new \model\UsersSQL($connection);
  }

  public function generateUsersHTML() {
    $users = $this->getUsers();
    $html = '<div class="base-content">';

    if ($users->num_rows > 0) {
      $html .= '<ul id="users-list">';
      while ($user = $users->fetch_assoc()) {
        $html .= '<li class="user"><label>' . $user["username"];
        if (isset($user["overall"])) {
          $html .= ', amount: ' . $user["overall"];
        }

        $html .= '</label></li>';
      }
      $html .= '</ul>
      <div class="dropdown">
        <button class="dropbtn">Sorting options</button>
        <div class="dropdown-content">
          <a href="/db_project/index.php?users=">None</a>
          <a href="/db_project/index.php?users=overall-descending">Overall xp descending</a>
          <a href="/db_project/index.php?users=combined-overall-descending">Combined overall</a>
          <a href="/db_project/index.php?users=most-trackings-descending">Most trackings</a>
        </div>
      </div>';
    } else {
      $html .= '<h1>No users found.</h1>';
    }

    $html .= '</div>';

    return $html;
  }

  private function getUsers() {
    if ($_GET["users"] == 'overall-descending') {
      return $this->usersSQL->getUsersOrderedByHighestOverallXpGained();
    } else if ($_GET["users"] == 'combined-overall-descending') {
      return $this->usersSQL->getUsersOrderedByHighestCombinedOverallXp();
    } else if ($_GET["users"] == 'most-trackings-descending') {
      return $this->usersSQL->getUsersOrderedByMostTrackings();
    }

    return $this->usersSQL->getUsers();
  }
}
?>