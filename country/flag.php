echo "<div class='football-card'>";

echo "<img src='images/clubs/" . $badge . "'
          alt='" . $club_name . "'
          class='football-image'>";


 echo "<div class='country-card'>";

// Coloured block instead of image
echo "<div class='flag-swatch' style='background-color: {$flag_color};'></div>";



echo "<p class='country-info'>
        <strong>$country_name</strong> ($short_name)<br>
        Capital: $capital_city<br>
        Population: " . number_format($population) . "<br>
        Continent: $continent
      </p>";

echo "</div>";
