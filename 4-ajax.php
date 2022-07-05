<?php
// ongeldig ajax request
if (!isset($_POST["req"])) { exit("INVALID REQUEST"); }
require "2-cal-core.php";
switch ($_POST["req"]) {
  // draw kalender voor maand
  case "draw":
    // aantal dagen in maand
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $_POST["month"], $_POST["year"]);
    // eerste en laaste dag maand
    $dateFirst = "{$_POST["year"]}-{$_POST["month"]}-01";
    $dateLast = "{$_POST["year"]}-{$_POST["month"]}-{$daysInMonth}";
    // dag van week
    //0 is zondag
    $dayFirst = (new DateTime($dateFirst))->format("w");
    $dayLast = (new DateTime($dateLast))->format("w");

    //namen
    $sunFirst = true;
    $days = ["Maandag", "Dinsdag", "Woensdag", "Donderdag", "Vrijdag", "Zaterdag"];
    if ($sunFirst) { array_unshift($days, "Zondag"); }
    else { $days[] = "Zondag"; }
    foreach ($days as $d) { echo "<div class='calsq head'>$d</div>"; }
    unset($days);

    // lege plaatsen voor begin van maand
    if ($sunFirst) { $pad = $dayFirst; }
    else { $pad = $dayFirst==0 ? 6 : $dayFirst-1 ; }
    for ($i=0; $i<$pad; $i++) { echo "<div class='calsq blank'></div>"; }

    // (B4) draw dagen in maand
    $events = $_CAL->get($_POST["month"], $_POST["year"]);
    $nowMonth = date("n");
    $nowYear = date("Y");
    $nowDay = ($nowMonth==$_POST["month"] && $nowYear==$_POST["year"]) ? date("j") : 0 ;
    for ($day=1; $day<=$daysInMonth; $day++) { ?>
    <div class="calsq day<?=$day==$nowDay?" today":""?>" data-day="<?=$day?>">
      <div class="calnum"><?=$day?></div>
        <?php if (isset($events["d"][$day])) { foreach ($events["d"][$day] as $eid) { ?>
        <div class="calevt" data-eid="<?=$eid?>"
             style="background:<?=$events["e"][$eid]["evt_color"]?>">
          <?=$events["e"][$eid]["evt_trainer"]?>
        </div>
        <?php if ($day == $events["e"][$eid]["first"]) {
          echo "<div id='evt$eid' class='calninja'>".json_encode($events["e"][$eid])."</div>";
        }}} ?>
    </div>
    <?php }

    // lege plaatsen eind van de maand
    if ($sunFirst) { $pad = $dayLast==0 ? 6 : 6-$dayLast ; }
    else { $pad = $dayLast==0 ? 0 : 7-$dayLast ; }
    for ($i=0; $i<$pad; $i++) { echo "<div class='calsq blank'></div>"; }
    break;

  // opslaan event
  case "save":
    if (!is_numeric($_POST["eid"])) { $_POST["eid"] = null; }
    echo $_CAL->save(
      str_replace("T", " ", $_POST["start"]), str_replace("T", " ", $_POST["end"]), $_POST["txt"], $_POST["txt2"], $_POST["txt3"], $_POST["txt4"],$_POST["txt5"], $_POST["color"],
      isset($_POST["eid"]) ? $_POST["eid"] : null
    ) ? "OK" : $_CAL->error ;
    break;

  // verwijder event
  case "del":
    echo $_CAL->del($_POST["eid"])  ? "OK" : $_CAL->error ;
    break;
}
