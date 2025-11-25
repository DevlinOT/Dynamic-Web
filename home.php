<html>

<head>
  <link rel="stylesheet" href="css\styles.css">
  <link rel="icon" type="image/x-icon" href="images/football.png">
</head>


<?php

include 'db_connect.php';

// ===============================
// Fetch distinct data from database
// ===============================
// Divisions

$sql = "SELECT DISTINCT division From clubs ORDER BY division";
$result1 = $conn->query($sql);

// Club Colours

$sql = "SELECT DISTINCT colour_name FROM club_colours ORDER BY colour_name";
$result2 = $conn->query($sql);

$conn->close(); ?>


<body>

  <center><img src="images/stadium.png" width="100%">
    <p></p>
  </center>

  <div id="backdrop"></div>
  <div id="club_modal" class="box3">
    <button class="close-btn" onclick="closeModal()">×</button>
    <div id="club_modal_content"></div>
  </div>

  <div class="div-border table-layout">
    <table id="shoppingtable">
      <tr class="shoptr">
        <td id="form-cell">
          <form id="club_form" action="/submit">
            <select name="division" id="division" onchange="updateClubList()">
              <option value="" disabled selected>Select a Division</option>
              <option value="">All</option>
              <?php while ($row = $result1->fetch_assoc()) {
                echo "<option value=\"" . $row["division"] . "\">" . $row["division"] . "</option>";
              } ?>
            </select>

            <div class="colour-checkboxes">
              <b>Select Colour:</b>
              <?php while ($row = $result2->fetch_assoc()) { ?>
              <label>
                <input type="checkbox" name="colours[]" value="<?php echo $row['colour_name']; ?>"
                 onchange="updateClubList()">
                 <?php echo $row['colour_name']; ?>
                </label>
              <?php } ?>
            </div>



            <div class="stadium-range">
              <div class="capacity-field">
                <label for="min_capacity">Min stadium capacity:</label>
                <input type="number" id="min_capacity" name="min_capacity" placeholder="0" min="0" onkeyup="updateClubList()">
                </div>
                <div class="capacity-field">
                <label for="max_capacity">Max stadium capacity:</label>
                <input type="number" id="max_capacity" name="max_capacity" placeholder="100000" min="0" max="100000" onkeyup="updateClubList()">
                </div>
              </div>
          


          <div class="sort-options">
  <b style="color:black;">Order By:</b><br>

  <label>
    <input type="radio" name="orderBy" value="name" onchange="updateClubList()">
    Club Name
  </label>

  <label>
    <input type="radio" name="orderBy" value="titles_serie_a" onchange="updateClubList()">
    Serie A Titles
  </label>

  <label>
    <input type="radio" name="orderBy" value="titles_coppa_italia" onchange="updateClubList()">
    Coppa Italias
  </label>

  <label>
    <input type="radio" name="orderBy" value="stadium_capacity" onchange="updateClubList()">
    Stadium Size
  </label>

  <label>
    <input type="radio" name="orderBy" value="stadium_city" onchange="updateClubList()">
    City
  </label>

  <label>
    <input type="radio" name="orderBy" value="division" onchange="updateClubList()">
    Division
  </label>
</div>


<div class="sort-direction">
  <b style="color:black;">Direction:</b><br>

  <label>
    <input type="radio" name="orderDir" value="ASC" checked onchange="updateClubList()">
    Ascending
  </label>

  <label>
    <input type="radio" name="orderDir" value="DESC" onchange="updateClubList()">
    Descending
  </label>
</div>


</form>

<div id="backdrop" onclick="closeModal()"></div>

<div id="club_modal">
  <div id="club_modal_content">
    <!-- clubmodalshow.php content (including Google Map) will be injected here -->
  </div>
  <button class="modal-close-button" onclick="closeModal()">Close</button>
</div>


        </td>
        <td id="response-cell">
          <div id="club_response" class="div-border"></div>
        </td>
      </tr>
    </table>
  </div>

</body>

<script src="js/clubs.js"></script>

</html>