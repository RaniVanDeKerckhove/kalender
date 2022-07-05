<?php
class Calendar {
  // constructor --> conector database
  private $pdo = null;
  private $stmt = null;
  public $error = "";
  function __construct () {
    try {
      $this->pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET,
        DB_USER, DB_PASSWORD, [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
      );
    } catch (Exception $ex) { exit($ex->getMessage()); }
  }

  // sluit database connectie
  function __destruct () {
    if ($this->stmt!==null) { $this->stmt = null; }
    if ($this->pdo!==null) { $this->pdo = null; }
  }

  // sql query
  function exec ($sql, $data=null) {
    try {
      $this->stmt = $this->pdo->prepare($sql);
      $this->stmt->execute($data);
      return true;
    } catch (Exception $ex) {
      $this->error = $ex->getMessage();
      return false;
    }
  }

  // event opslaan
  function save ($start, $end, $txt, $txt2, $txt3, $txt4, $txt5, $color, $id=null) {
     // start en eind datum
     $uStart = strtotime($start);
     $uEnd = strtotime($end);
     if ($uEnd < $uStart) {
       $this->error = "begin uur kan niet later dan eind uur!";
       return false;
     }

    // insert en update
    if ($id==null) {
      $sql = "INSERT INTO `events` (`evt_trainer`,`evt_lokaal`, `evt_bezigheid`,`evt_materiaal`,`evt_andere`,`evt_start`, `evt_end`, `evt_color`) VALUES (?,?,?,?,?,?,?,?)";
      $data = [$txt, $txt2, $txt3, $txt4, $txt5, $start, $end, $color];
    } else {
      $sql = "UPDATE `events` SET evt_trainer = ?, evt_lokaal = ?, evt_bezigheid = ?, evt_materiaal = ?, evt_andere = ?, evt_start = ?, evt_end = ?, evt_color = ? WHERE evt_id = ?";
      $data = [$txt, $txt2, $txt3, $txt4, $txt5, $start, $end, $color, $id];
    }

    return $this->exec($sql, $data);
  }

  // verwijder event
  function del ($id) {
    return $this->exec("DELETE FROM `events` WHERE `evt_id`=?", [$id]);
  }

  // geef evenementen voor geselecteerde maand
  function get ($month, $year) {
    // eerste en laatste dag van de maand
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $dayFirst = "{$year}-{$month}-01 00:00:00";
    $dayLast = "{$year}-{$month}-{$daysInMonth} 23:59:59";

    // geef events
    if (!$this->exec(
      "SELECT * FROM `events` WHERE (
        (`evt_start` BETWEEN ? AND ?)
        OR (`evt_end` BETWEEN ? AND ?)
        OR (`evt_start` <= ? AND `evt_end` >= ?)
      )", [$dayFirst, $dayLast, $dayFirst, $dayLast, $dayFirst, $dayLast]
    )) { return false; }

    $events = ["e" => [], "d" => []];
    while ($row = $this->stmt->fetch()) {
      $eStartMonth = substr($row["evt_start"], 5, 2);
      $eEndMonth = substr($row["evt_end"], 5, 2);
      $eStartDay = $eStartMonth==$month
                 ? (int)substr($row["evt_start"], 8, 2) : 1 ;
      $eEndDay = $eEndMonth==$month
               ? (int)substr($row["evt_end"], 8, 2) : $daysInMonth ;
      for ($d=$eStartDay; $d<=$eEndDay; $d++) {
        if (!isset($events["d"][$d])) { $events["d"][$d] = []; }
        $events["d"][$d][] = $row["evt_id"];
      }
      $events["e"][$row["evt_id"]] = $row;
      $events["e"][$row["evt_id"]]["first"] = $eStartDay;
    }
    return $events;
  }
}

// database
define("DB_HOST", "localhost");
define("DB_NAME", "kalender");
define("DB_CHARSET", "utf8");
define("DB_USER", "root");
define("DB_PASSWORD", "");

// nieuw kalender object
$_CAL = new Calendar();
