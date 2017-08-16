


    <?php
    //UPDATE users SET sms_number = '+255654988047' WHERE id=1007
    include 'dbcon.php';

      $filtervalue = $_GET['filter'];

        if ($filtervalue == "notclear") {
          $drainsql = "SELECT * FROM mitaro_dar WHERE cleared = false";
          $tabletitle = "MICHAFU";
        } else if ($filtervalue == "clear")  {
          $drainsql = "SELECT * FROM mitaro_dar WHERE cleared = true";
          $tabletitle = "MISAFI";
        }
        else if ($filtervalue == "help")  {
          $drainsql = "SELECT * FROM mitaro_dar WHERE need_help = true";
          $tabletitle = "INAYOHITAJI MSAADA";
        }

        $filterdrain = pg_query($dbcon,$drainsql);
        //$r = pg_num_rows($filterdrain);

      $numrows = pg_num_rows($filterdrain);

      //echo $numrows;

      // number of rows to show per page
      $rowsperpage = 200;
      // find out total pages
      $totalpages = ceil($numrows / $rowsperpage);

      // get the current page or set a default
      if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
         // cast var as int
         $currentpage = (int) $_GET['currentpage'];
      } else {
         // default page num
         $currentpage = 1;
      } // end if

      // if current page is greater than total pages...
      if ($currentpage > $totalpages) {
         // set current page to last page
         $currentpage = $totalpages;
      } // end if
      // if current page is less than first page...
      if ($currentpage < 1) {
         // set current page to first page
         $currentpage = 1;
      } // end if

      // the offset of the list, based on current page 
      $offset = ($currentpage - 1) * $rowsperpage;

      $dardrain = pg_query($dbcon, "SELECT * 
        FROM mitaro_dar 
        LIMIT  $rowsperpage OFFSET $offset");

        if ($numrows > 1) { 
    ?>


   <table class="w3-table w3-hoverable w3-responsive w3-white" border="0">
        <tr class="w3-light-grey w3-border-bottom">
        <th class="w3-center w3-dark-grey" colspan="5">MITARO <?php echo $tabletitle; ?></th></tr>
      <tr class="w3-light-grey w3-border-bottom">
        <th>Namba ya Mtaro</th>
        <th>Jina la Mtaro</th>
        <th>Mtaa</th>
        <th>Mhusika</th>
        <th>Hali</th>
      </tr>
      <?php      
        while($drain_row=pg_fetch_assoc($filterdrain)) {
      ?>
      <tr>
        <td><?php echo $drain_row["gid"]; ?></td>
        <td><?php echo $drain_row["name"];  ?></td>
        <td><?php echo $drain_row["address"]; ?></td>
        <td>
          <?php 
             $drainId=$drain_row['gid'];
            //echo($drainId);
            $sqlCitizen = pg_query($dbcon,"SELECT * FROM sidewalk_claims WHERE gid=$drainId");

            if((pg_num_rows($sqlCitizen)) > 0) {
              $citizenInfo=pg_fetch_assoc($sqlCitizen);
              $citizenId=$citizenInfo["user_id"];
              $citizen = pg_query($dbcon,"SELECT * FROM users WHERE id=$citizenId");
              $citizenName=pg_fetch_assoc($citizen);
              $mhusika = $citizenName["first_name"]." ".$citizenName["last_name"];
              $number = $citizenName["sms_number"];
              echo $mhusika;
            } else {
              echo "Not Claimed";
              
            }
            

           ?>
            
        </td>
        <td>
          <a href="<?php echo 'functions/notClear.php?thedrain='.$drainId; ?>"><button id="siomsafi" name="" class="btn warning" >MCHAFU</button></a>
          <a href="<?php echo 'functions/clear.php?thedrain='.$drainId; ?>"><button id="msafi" name="" class="btn success">MSAFI</button></a> 
        </td>
        </td>
         <?php } //End While  
        
         ?>
      </tr>

      </table>

      <div class="w3-center">
<?php

$range = 3;

// if not on page 1, don't show back links
if ($currentpage > 1) {
   // show << link to go back to page 1
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=1'><button class=\"w3-btn w3-blue w3-round-xxlarge\"><< First Page </button></a> ";
   // get previous page num
   $prevpage = $currentpage - 1;
   // show < link to go back to 1 page
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$prevpage'><button class=\"w3-btn w3-blue w3-round-xxlarge w3-margin-right\">< PreviousPage</button></a> ";
} // end if 

// loop to show links to range of pages around current page
for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
   // if it's a valid page number...
   if (($x > 0) && ($x <= $totalpages)) {
      // if we're on current page...
      if ($x == $currentpage) {
         // 'highlight' it but don't make a link
         echo " <button class=\"w3-button w3-blue w3-circle\"><b>$x</b></button> ";
      // if not current page...
      } else {
         // make it a link
         echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$x'><button class=\"w3-btn w3-grey w3-circle\">$x</button></a> ";
      } // end else
   } // end if 
} // end for
                 
// if not on last page, show forward and last page links        
if ($currentpage != $totalpages) {
   // get next page
   $nextpage = $currentpage + 1;
    // echo forward link for next page 
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage'><button class=\"w3-btn w3-blue w3-round-xxlarge w3-margin-left\">Next Page ></button></a> ";
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$totalpages'><button class=\"w3-btn w3-blue w3-round-xxlarge\">Last Page >></button></a> ";
} // end if
/****** end build pagination links ******/
 
?>
  <?php   } // End If 
         else {
          echo "<p class=\"w3-center w3-padding\"> Hakuna mitaro inayohitaji msaada</p>";
          } ?>
</div>
  <script>

    function notClear() {
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
         document.getElementById("serverResult").innerHTML = this.responseText;
        }
      };
      xhttp.open("GET", "notClear.php", true);
      xhttp.send();
    }

    </script>
     


  <!-- AJAX Scrits for Button Actions -->
  <script>

  </script>
</body>
</html>

