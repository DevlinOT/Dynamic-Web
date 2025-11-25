<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Loaded Content</title>
</head>

<body>

    <?php
    // ===============================
    // Get POST Data
    // ===============================
    $stadium_city = isset($_POST['stadium_city']) ? htmlspecialchars($_POST['stadium_city']) : '';
    $stadium_name = isset($_POST['stadium_name']) ? htmlspecialchars($_POST['stadium_name']) : '';
    $club_name = isset($_POST['club_name']) ? htmlspecialchars($_POST['club_name']) : '';



    // Encode for Google Maps
    $stadium_location = "";
    if ($stadium_name) {
        $stadium_location .= urlencode($stadium_name) . " , ";
    }
    $stadium_location .= urlencode($stadium_city);

    // ===============================
    // Database Query
    // ===============================
    include 'db_connect.php';
    $sql_statement = "SELECT * FROM clubs WHERE name = '$club_name' LIMIT 1";
    $result = $conn->query($sql_statement);
    $conn->close();

    // ===============================
    // Container for football Card and Map
    // ===============================
    echo "<div class='stadium-map-container'>";
    // ===============================
    // football Card Section
    // ===============================
    echo "<div class='football-container'>";
    while ($row = mysqli_fetch_array($result)) {
        echo "<div class='football-card'>
                <img src='images/clubs/" . htmlspecialchars($row['badge_filename']) . "' 
                     alt='" . htmlspecialchars($row['short_name']) . "' 
                     class='football-image'>
                <p class='football-info'>Team: " . htmlspecialchars($row['short_name']) . "</p>
                <p class='football-info'>Division: " . htmlspecialchars($row['division']) . "</p>
                <p class='football-info'>Stadium: " . htmlspecialchars($row['stadium_name']) . "</p>
                <p class='football-info'>Capacity: " . htmlspecialchars($row['stadium_capacity']) . "</p>
              </div>";
    }
    echo "</div>";

    // ===============================
    // Google Maps Section
    // ===============================


if ($stadium_city == "Italy") {
    echo "<iframe 
        class='country-map' 
        loading='lazy' 
        referrerpolicy='no-referrer-when-downgrade' 
        src='https://www.google.com/maps?q={$stadium_location}&output=embed&t=k&z=6'>
    </iframe>";
} else {
    echo "<iframe 
        class='country-map' 
        loading='lazy' 
        referrerpolicy='no-referrer-when-downgrade' 
        src='https://www.google.com/maps?q={$stadium_location}&output=embed&t=k'>
    </iframe>";
}

    echo "</div>"; // end stadium-map-container
    ?>

</body>

</html>