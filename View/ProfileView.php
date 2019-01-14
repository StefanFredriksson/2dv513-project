<?php
namespace view;

class ProfileView {
  private $connection;
  private $runescapeSQL;

  public function __construct($connection) {
    $this->connection = $connection;
    $this->runescapeSQL = new \model\RunescapeSQL($connection);
  }
  public function generateProfilePage() {
    $linkHtml = '<div id="profile-content">
    <h1 id="profile-h1">' . $_SESSION["username"] . ' profile page</h1>
    ' . $this->getClosedTrackers() . '
    ' . $this->getOpenTrackers() . '
    </div>
    ';
    return $linkHtml;
  }

  private function getClosedTrackers() {
    $ids = $this->runescapeSQL->getClosedTrackings($_SESSION["username"]);
    $html = '';

    if ($ids->num_rows > 0) {
      $html = '<div id="closed-tracking">
      <h1>Closed tracking</h1>
      <ul id="closed-list">';

      while ($id = $ids->fetch_assoc()) {
        $html .= '<li class="profile-trackers"><a href="/db_project/index.php?closed-id=' . $id["id"] . '">track id ' . $id["id"] . '</a></li>';
      }
      $html .= '</ul></div>';
    }

    return $html;
  }

  private function getOpenTrackers() {
    $ids = $this->runescapeSQL->getOpenTrackings($_SESSION["username"]);
    $html = '';

    if ($ids->num_rows > 0) {
      $html = '<div id="open-tracking">
      <h1>Open tracking</h1>
      <ul id="open-list">';

      while ($id = $ids->fetch_assoc()) {
        $html .= '<li class="profile-trackers"><a href="/db_project/index.php?open-id=' . $id["id"] . '">track id ' . $id["id"] . '</a></li>';
      }
      $html .= '</ul></div>';
    }

    return $html;
  }
}
?>