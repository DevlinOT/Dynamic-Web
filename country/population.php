<!-- OLD -->
<label>Stadium Capacity From:</label>

<!-- NEW -->
<label>Population From:</label>



<!-- OLD -->
<input type="number" name="capacity_min">
<input type="number" name="capacity_max">

<!-- NEW -->
<input type="number" name="population_min">
<input type="number" name="population_max">


// OLD
if (!empty($_POST['capacity_min'])) {
    $filters[] = "stadium_capacity >= " . (int)$_POST['capacity_min'];
}
if (!empty($_POST['capacity_max'])) {
    $filters[] = "stadium_capacity <= " . (int)$_POST['capacity_max'];
}

// NEW
if (!empty($_POST['population_min'])) {
    $filters[] = "population >= " . (int)$_POST['population_min'];
}
if (!empty($_POST['population_max'])) {
    $filters[] = "population <= " . (int)$_POST['population_max'];
}


<label>
  <input type="radio" name="orderBy" value="name" onchange="updateClubList()">
  Country Name
</label>

<label>
  <input type="radio" name="orderBy" value="population" onchange="updateClubList()">
  Population
</label>

<label>
  <input type="radio" name="orderBy" value="capital_city" onchange="updateClubList()">
  Capital City
</label>

<label>
  <input type="radio" name="orderBy" value="continent" onchange="updateClubList()">
  Continent
</label>





$allowedColumns = [
    'name',
    'population',
    'capital_city',
    'continent',
    'region'
];


$flag_color1 = htmlspecialchars($row['flag_colour_primary']);
$flag_color2 = htmlspecialchars($row['flag_colour_secondary']);

echo "<div class='flag-swatch'
          style='background: linear-gradient(to right, {$flag_color1} 50%, {$flag_color2} 50%);'>
      </div>";
