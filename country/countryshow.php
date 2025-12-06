<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Countries</title>
</head>

<body>

    <?php
    // FILTERS COMING FROM home.php (you'll need to update the form names to match)
    $region   = isset($_POST['region'])   ? htmlspecialchars($_POST['region'])   : '';
    $colours  = isset($_POST['colours'])  ? $_POST['colours']                    : [];
    $min_pop  = isset($_POST['min_pop'])  ? (int) $_POST['min_pop']              : 0;
    $max_pop  = isset($_POST['max_pop'])  ? (int) $_POST['max_pop']              : 2000000000;
    $orderByColumnName = isset($_POST['orderBy']) ? $_POST['orderBy']           : '';
    $orderDir          = isset($_POST['orderDir']) ? $_POST['orderDir']         : "DESC";

    include 'db_connect.php';

    $opening_part        = "SELECT * FROM countries";
    $region_clause       = "";
    $colour_clause       = "";
    $min_pop_clause      = "";
    $max_pop_clause      = "";
    $orderby_clause      = "";
    $extra               = " WHERE";

    // REGION / CONTINENT FILTER (like your division)
    if ($region) {
        $region_clause = "$extra region='$region'";
        $extra = " AND";
    }

    // COLOUR FILTER (if you still want to filter by colours stored in JSON)
    if (!empty($colours)) {
        $colour_conditions = [];
        foreach ($colours as $colour) {
            $colour = htmlspecialchars($colour);
            $colour_conditions[] = "JSON_CONTAINS(colours, '\"$colour\"')";
        }
        $colour_clause = "$extra (" . implode(" AND ", $colour_conditions) . ")";
        $extra = " AND";
    }

    // POPULATION RANGE (replaces stadium_capacity)
    if ($min_pop) {
        $min_pop_clause = "$extra population >= $min_pop";
        $extra = " AND";
    }

    if ($max_pop) {
        $max_pop_clause = "$extra population <= $max_pop";
        $extra = " AND";
    }

    // ORDER BY (you might whitelist allowed columns here if you like)
    if ($orderByColumnName) {
        $orderByColumnName = htmlspecialchars($orderByColumnName);
        $orderDir = ($orderDir === 'ASC') ? 'ASC' : 'DESC';
        $orderby_clause = " ORDER BY $orderByColumnName $orderDir";
    }

    $sql_statement = "$opening_part $region_clause$colour_clause$min_pop_clause$max_pop_clause$orderby_clause";

    $result = $conn->query($sql_statement);
    $conn->close();

    $num_results = mysqli_num_rows($result);

    if ($num_results > 1) {
        echo "<b><h3 class='result-count'>There are " . $num_results . " Results</h3></b><br>";
    } else {
        echo "<br>";
    }

    echo "<div class='country-container div-border'>";

    while ($row = mysqli_fetch_array($result)) {
        $country_name = htmlspecialchars($row['name']);
        $short_name   = htmlspecialchars($row['short_name']);
        $capital_city = htmlspecialchars($row['capital_city']);
        $continent    = htmlspecialchars($row['continent']);
        $region_name  = htmlspecialchars($row['region']);
        $population   = (int) $row['population'];
        $flag_colour  = htmlspecialchars($row['flag_colour_primary']); // e.g. "#008C45" or "green"

        echo "<div class='country-card'>";

        // COLOURED BLOCK INSTEAD OF BADGE IMAGE
        echo "<div class='flag-swatch' style='background-color: {$flag_colour};'></div>";

        // TEXT INFO
        echo "<p class='country-info'><strong>$country_name</strong> ($short_name)</p>";
        echo "<p class='country-info'>Capital:  📍 $capital_city</p>";
        echo "<p class='country-info'>Continent: $continent</p>";
        echo "<p class='country-info'>Region: $region_name</p>";
        echo "<p class='country-info'>Population: " . number_format($population) . "</p>";

        echo "</div>";
    }

    echo "</div>";
    ?>

</body>

</html>
