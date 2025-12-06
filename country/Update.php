// OLD:
$sql = "SELECT * FROM clubs WHERE 1";

// NEW:
$sql = "SELECT * FROM countries WHERE 1";





$club_name    = htmlspecialchars($row['name']);
$short_name   = htmlspecialchars($row['short_name']);
$stadium_name = htmlspecialchars($row['stadium_name']);
$stadium_city = htmlspecialchars($row['stadium_city']);
$capacity     = (int)$row['stadium_capacity'];
$badge        = htmlspecialchars($row['badge_filename']);


$country_name  = htmlspecialchars($row['name']);
$short_name    = htmlspecialchars($row['short_name']);
$capital_city  = htmlspecialchars($row['capital_city']);
$population    = (int)$row['population'];
$flag_color    = htmlspecialchars($row['flag_colour_primary']);
$continent     = htmlspecialchars($row['continent']);
