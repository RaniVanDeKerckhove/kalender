<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Kalender</title>
    <link rel="stylesheet" href="6-calendar.css">
    <script src="5-calendar.js"></script>
  </head>
  <body>
    <!-- periode selector  -->
    <div id="calPeriod"><?php
      // maand selector
      $months = [
        1 => "Januari", 2 => "Februari", 3 => "Maart", 4 => "April",
        5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Augustus",
        9 => "September", 10 => "October", 11 => "November", 12 => "December"
      ];
      $monthNow = date("m");
      echo "<select id='calmonth'>";
      foreach ($months as $m=>$mth) {
        printf("<option value='%s'%s>%s</option>",
          $m, $m==$monthNow?" selected":"", $mth
        );
      }
      echo "</select>";

      // jaar selector
      echo "<input type='number' id='calyear' value='".date("Y")."'/>";
    ?></div>

    <!--wrapper-->
    <div id="calwrap"></div>

    <!-- event formulier -->
    <div id="calblock"><form id="calform">
      <input type="hidden" name="req" value="save"/>
      <input type="hidden" id="evtid" name="eid"/>
      <label for="txt">Trainer</label>
      <textarea id="evttxt" name="txt" required></textarea>
      <label for="txt2">Lokaal</label>
      <textarea id="evttxt2" name="txt2" required></textarea>
      <label for="txt3">Omschrijving</label>
      <textarea id="evttxt3" name="txt3" required></textarea>
      <label for="txt4">Materiaal</label>
      <textarea id="evttxt4" name="txt4" required></textarea>
      <label for="txt5">Andere</label>
      <textarea id="evttxt5" name="txt5" required></textarea>
      <label for="start">Uur en datum</label>
      <input type="datetime-local" id="evtstart" name="start" required/>
      <label for="end">Eind uur en datum</label>
      <input type="datetime-local" id="evtend" name="end" required/>
      <label for="color">Kleur</label>
      <input type="color" id="evtcolor" name="color" value="#e4edff" required/>
      <input type="submit" id="calformsave" value="Opslaan"/>
      <input type="button" id="calformdel" value="Verwijderen"/>
      <input type="button" id="calformcx" value="Sluiten"/>
    </form></div>
  </body>
</html>
