<?php
namespace model;

class RunescapeSQL {
  private $connection;

  public function __construct($connection) {
    $this->connection = $connection;
  }

  public function saveStats($id, $username, $stats, $startTime) {
    $json = json_encode($stats);
    $json = addslashes($json);

    $sql = 'INSERT INTO xp_trackers (creator_id, username, xp, start_time) VALUES ("' . $id . '", "' . $username . '", "' . $json . '", ' . $startTime . ');';
    $this->connection->query($sql);
  }

  public function getOpenTrackings($username) {
    $sql = 'SELECT x.xp_id AS "id" FROM users u JOIN xp_trackers x ON u.user_id = x.creator_id WHERE u.username="' . $username . '" AND x.end_time IS NULL ORDER BY x.xp_id ASC;';
    return $this->connection->query($sql);
  }

  public function getOpenStats($id) {
    $sql = 'SELECT xp, username, start_time FROM xp_trackers WHERE xp_id="' . $id . '";';
    return $this->connection->query($sql);
  }

  public function getClosedStats($id) {
    $sql = 'SELECT xp, username, end_xp, start_time, end_time FROM xp_trackers WHERE xp_id="' . $id . '";';
    return $this->connection->query($sql);
  }

  public function stopTracking($id, $currentSkills, $time, $overall) {
    $json = json_encode($currentSkills);
    $json = addslashes($json);

    $sql = 'UPDATE xp_trackers SET end_xp="' . $json . '", end_time=' . $time . ', end_overall_xp=' . $overall . ' WHERE xp_id="' . $id . '";';
    $this->connection->query($sql);
  }

  public function getClosedTrackings($username) {
    $sql = 'SELECT x.xp_id AS "id" FROM xp_trackers x JOIN users u ON x.creator_id = u.user_id WHERE u.username="' . $username . '" AND x.end_time <> 0 ORDER BY x.xp_id ASC';
    return $this->connection->query($sql);
  }
}
?>