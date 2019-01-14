<?php
namespace view;

class RuneScapeView {
  private static $tracking = "runescape-tracking";
  private $runescape;
  public function __construct() {
    $this->runescape = new \model\RuneScape();
  }

  public function generateRuneScapeUser() {
    return $this->getData();
  }

  private function getData() {
    $submitBtn = '';
    $data = $this->runescape->getRuneScapeUser($_GET["name"]);

    if (isset($_SESSION["loggedIn"])) {
      if ($_SESSION["loggedIn"]) {
        $submitBtn = '<button type="submit" class="pretty-button" name="' . self::$tracking . '">Track</button>';
      }
    }
    $html = '
    <div class="base-content stats-div">
    <h1>' . $_GET["name"] . '</h1>
      <form method="post">
        <table id="runescape-stats">
          <th>Skill</th>
          <th>XP</th>
          <th>Goal</th>
    ';

    foreach($data as $skill) {
      $html .= '
      <tr>
        <td class="skill-name"><label>' . $skill->getName() . '</label></td>
        <td class="skill-xp"><label>' . number_format($skill->getXp()) . '</label></td>';

        if (isset($_SESSION["loggedIn"])) {
          if ($_SESSION["loggedIn"]) {
            $html .= '
            <td><input type="checkbox" name="' . $skill->getName() . '" value="' . $skill->getXp() . '" ></td>
            </tr>
            ';
          } else {
            $html .= '</tr>';
          }
        } else {
          $html .= '</tr>';
        }
    }
    $html .= '</table>
    ' . $submitBtn . '
      </form>
    </div>';
    return $html;
  }

  public function wantsToTrack() {
    return isset($_POST[self::$tracking]);
  }

  public function getStatsToTrack() {
    $stats = array();

    foreach($_POST as $key => $value) {
      if ($key != self::$tracking) {
        $stats[] = new \model\Skill($key, $value);
      }
    }

    return $stats;
  }
}
?>