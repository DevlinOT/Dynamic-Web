<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Italian Football Clubs</title>
</head>

<body>

    <?php

    $division = isset($_POST['division']) ? htmlspecialchars($_POST['division']) : '';
    $colours = isset($_POST['colours']) ? $_POST['colours'] : [];
    $min_capacity = isset($_POST['min_capacity']) ? (int) $_POST['min_capacity'] : 0;
    $max_capacity = isset($_POST['max_capacity']) ? (int) $_POST['max_capacity'] : 100000;
    $orderByColumnName = isset($_POST['orderBy']) ? $_POST['orderBy'] : '';
    $orderDir = isset($_POST['orderDir']) ? $_POST['orderDir'] : "DESC";

    include 'db_connect.php';

    $opening_part = "SELECT * FROM clubs";

    $division_clause = "";
    $colour_clause = "";
    $min_capacity_clause = "";
    $max_capacity_clause = "";
    $orderby_clause = "";
    $extra = " WHERE";

    if ($division) {
        $division_clause = "$extra division='$division'";
        $extra = " AND";
    }

    if (!empty($colours)) {
        $colour_conditions = [];
        foreach ($colours as $colour) {
            $colour = htmlspecialchars($colour);
            $colour_conditions[] = "JSON_CONTAINS(colours, '\"$colour\"')";
        }
        // Use AND instead of OR so all selected colours must be present
        $colour_clause = "$extra (" . implode(" AND ", $colour_conditions) . ")";
        $extra = " AND";
    }

    if ($min_capacity) {
        $min_capacity_clause = "$extra stadium_capacity >= $min_capacity";
        $extra = " AND";
    }

    if ($max_capacity) {
        $max_capacity_clause = "$extra stadium_capacity <= $max_capacity";
        $extra = " AND";
    }

    if ($orderByColumnName) {
        $orderby_clause = " ORDER BY $orderByColumnName $orderDir";
    }

    $sql_statement = "$opening_part $division_clause$colour_clause$min_capacity_clause$max_capacity_clause$orderby_clause";

    $result = $conn->query($sql_statement);
    $conn->close();

    $num_results = mysqli_num_rows($result);

    if ($num_results > 1) {
        echo "<b><h3 class='result-count'>There are " . mysqli_num_rows($result) . " Results</h3></b><br>";
    } else {
        echo "<br>";
    }

    echo "<div class='football-container div-border'>";

    while ($row = mysqli_fetch_array($result)) {
        $club_name = htmlspecialchars($row['name']);
        $short_name = htmlspecialchars($row['short_name']);
        $stadium_name = htmlspecialchars($row['stadium_name']);
        $stadium_city = htmlspecialchars($row['stadium_city']);

        $jsStadiumName = json_encode($stadium_name);
        $jsStadiumCity = json_encode($stadium_city);

        echo "<div class='football-card'>";

        // Click on the badge — show modal with stadium name and city
        echo "<img src='images/clubs/" . $row['badge_filename'] . "' 
              alt='" . $club_name . "' 
              class='football-image hover-pointer' 
              onclick='openClubModal(\"$stadium_name\", \"$stadium_city\", \"$club_name\")'>";

        // Club name (no click)
        echo "<p class='football-info'><strong>" . $short_name . "</strong></p>";

        // Stadium name — click to open modal with stadium name and city
        echo "<p class='football-info hover-pointer'
              onclick='openClubModal(\"$stadium_name\", \"$stadium_city\", \"$club_name\")'>
              Stadium: 📍 $stadium_name 
              </p>";

        echo "<p class='football-info hover-pointer' 
              onclick='openClubModal(\"$stadium_city\", \"Italy\", \"$club_name\")'>
              City:  📍 $stadium_city 
          </p>";


        echo "<p class='football-info'>Division: " . $row['division'] . "</p>";
        echo "<p class='football-info'>Capacity: " . number_format($row['stadium_capacity']) . "</p>";

        // Display titles if they have any
        $titles = [];
        if ($row['titles_serie_a'] > 0)
            $titles[] = "Serie A: " . $row['titles_serie_a'];
        if ($row['titles_coppa_italia'] > 0)
            $titles[] = "Coppa Italia: " . $row['titles_coppa_italia'];
        if ($row['titles_european_cups'] > 0)
            $titles[] = "European: " . $row['titles_european_cups'];

        if (!empty($titles)) {
            echo "<p class='football-info'><em>" . implode(", ", $titles) . "</em></p>";
        }

        echo "</div>";
    }

    echo "</div>";

    ?>

</body>

</html>