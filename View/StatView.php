<?php
namespace view;

class StatView {
  private static $stopTracking = 'stop-tracking';
  private $runescapeSQL;
  private $runescape;

  public function __construct($connection) {
    $this->runescapeSQL = new \model\RunescapeSQL($connection);
    $this->runescape = new \model\RuneScape();
  }

  public function generateStatPage() {
    $stats;
    $closed = false;
    $query = 'open-id';

    foreach($_GET as $key => $value) {
      if ($key == 'closed-id') {
        $closed = true;
        $query = 'closed-id';
      }
    }
    if ($closed) {
      $stats = $this->runescapeSQL->getClosedStats($_GET[$query]);
    } else {
      $stats = $this->runescapeSQL->getOpenStats($_GET[$query]);
    }
    $stats = $stats->fetch_assoc();
    $username = $stats["username"];
    $startTime = $stats["start_time"];
    $endTime = time();

    if($closed) {
      $endTime = $stats["end_time"];
    }
    $time = $this->secondsToTime($startTime, $endTime);

    $years = $time["years"] . ' years ';
    $months = $time["months"] . ' months ';
    $days = $time["days"] . ' days ';
    $hours = $time["hours"] . ' hours ';
    $minutes = $time["minutes"] . ' minutes ';
    $seconds = $time["seconds"] . ' seconds';

    if ($time["years"] == 0) {
      $years = '';
    } else if ($time["years"] == 1) {
      $years = $time["years"] . ' year ';
    }

    if ($time["months"] == 0) {
      $months = '';
    } else if ($time["months"] == 1) {
      $months = $time["months"] . ' month ';
    }

    if ($time["days"] == 0) {
      $days = '';
    } else if ($time["days"] == 1) {
      $days = $time["days"] . ' day ';
    }

    if ($time["hours"] == 0) {
      $hours = '';
    } else if ($time["hours"] == 1) {
      $hours = $time["hours"] . ' hour ';
    }

    if ($time["minutes"] == 0) {
      $minutes = '';
    } else if ($time["minutes"] == 1) {
      $minutes = $time["minutes"] . ' minute ';
    }

    if ($time["seconds"] == 1) {
      $seconds = $time["seconds"] . ' second';
    }

    $creation = '<h1>Time since creation: ' . $years . $months . $days . $hours . $minutes . $seconds . '.</h1>';

    if ($closed) {
      $creation = '<h1>Tracking was open for: ' . $years . $months . $days . $hours . $minutes . $seconds . '.</h1>';
    }

    $currentSkills;
    $skills = $this->getSkills($stats["xp"]);
    if (!$closed) {
      $currentStats = $this->runescape->getRuneScapeUser($username);
      $currentSkills = $this->getTrimmedStats($skills, $currentStats);
    } else {
      $currentSkills = $this->getSkills($stats["end_xp"]);
    }
    $xpGained = $this->getXpGained($skills, $currentSkills);
    $xpPerHour = $this->getXpPerHour($xpGained, $endTime - $startTime);
    $html = '
    <div class="base-content stats-div">
    <h1>' . $username . '</h1>
    ' . $creation . '
    <table>
    <th>Skill</th>
    <th>Original XP</th>
    <th>Current XP</th>
    <th>XP Gained</th>
    <th>XP per hour</th>';

    for($i = 0; $i < count($skills); $i++) {
      $html .= '<tr>
      <td class="skill-name"><label>' . $skills[$i]->getName() . '</label></td>
      <td class="skill-xp"><label>' . number_format($skills[$i]->getXp()) . '</label></td>
      <td class="skill-xp"><label>' . number_format($currentSkills[$i]->getXp()) . '</label></td>
      <td class="skill-xp"><label>' . number_format($xpGained[$i]) . '</label></td>
      <td class="skill-xp"><label>' . number_format($xpPerHour[$i]) . '</label></td>
      </tr>';
    }

    if (!$closed) {
      $html .= '</table>
      <form method="post">
        <button type="submit" class="pretty-button" name="' . self::$stopTracking . '">Close tracking</button>
      </form>';
    }

    $html .= '</div>';
    return $html;
  }

  private function secondsToTime($startTime, $endTime) {
    $then = new \DateTime(date('Y-m-d H:i:s', $startTime));
    $now = new \DateTime(date('Y-m-d H:i:s', $endTime));
    $diff = $then->diff($now);
    return array('years' => $diff->y, 'months' => $diff->m, 'days' => $diff->d, 'hours' => $diff->h, 'minutes' => $diff->i, 'seconds' => $diff->s);
  }

  public function getSkills($stats) {
    $skills = array();
    $json = json_decode($stats, true);

    foreach($json as $skill) {
      $skills[] = new \model\Skill($skill["name"], $skill["xp"]);
    }

    return $skills;
  }

  public function getTrimmedStats($oldStats, $currentStats) {
    $stats = array();
    foreach($oldStats as $stat) {
      foreach($currentStats as $newStat) {
        if ($stat->getName() == $newStat->getName()) {
          $stats[] = $newStat;
          break;
        }
      }
    }

    return $stats;
  }

  private function getXpGained($oldStats, $newStats) {
    $xpGained = array();

    for($i = 0; $i < count($oldStats); $i++) {
      $xpGained[] = $newStats[$i]->getXp() - $oldStats[$i]->getXp();
    }

    return $xpGained;
  }

  private function getXpPerHour($xpGained, $time) {
    $xpPerHour = array();

    foreach($xpGained as $xp) {
      $xpPerHour[] = $xp / (($time / 60) / 60);
    }

    return $xpPerHour;
  }

  public function wantsToStopTracking() {
    return isset($_POST[self::$stopTracking]);
  }

  public function getId() {
    foreach ($_GET as $key => $value) {
      if ($key == 'closed-id') {
        return $_GET["closed-id"];
      }
    }
    return $_GET["open-id"];
  }
}
?>