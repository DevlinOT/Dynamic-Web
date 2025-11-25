
<!DOCTYPE html>
<html>
<head>
    <title>Creating Italian Football Clubs Database</title>
</head>
<body>

<?php

// include the connection file
include '../db_info.php';

$conn = new mysqli($servername, $username, $password, NULL, $port);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Drop database if exists
$sql = "DROP DATABASE IF EXISTS $dbname;";
if ($conn->query($sql) === TRUE) {
  echo "Database dropped successfully<br>";
} else {
  echo "Error dropping database: " . $conn->error;
}

// Create database
$sql = "CREATE DATABASE $dbname;";
if ($conn->query($sql) === TRUE) {
  echo "Database created successfully<br>";
} else {
  echo "Error creating database: " . $conn->error;
}

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// sql to create clubs table
$sql = "CREATE TABLE clubs (
  club_id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(128) NOT NULL,
  short_name VARCHAR(64),
  division VARCHAR(32) NOT NULL,
  badge_filename VARCHAR(255),
  stadium_name VARCHAR(255),
  stadium_city VARCHAR(128),
  stadium_address VARCHAR(255),
  stadium_capacity INT,
  titles_serie_a INT DEFAULT 0,
  titles_serie_b INT DEFAULT 0,
  titles_coppa_italia INT DEFAULT 0,
  titles_european_cups INT DEFAULT 0,
  titles_uefa INT DEFAULT 0,
  titles_cwc INT DEFAULT 0,
  colours JSON NOT NULL
);";

if ($conn->query($sql) === TRUE) {
  echo "Table clubs created successfully<br>";
} else {
  echo "Error creating table: " . $conn->error;
}

// sql to create club_colours table
$sql = "CREATE TABLE club_colours (
    colour_id INT AUTO_INCREMENT PRIMARY KEY,
    colour_name VARCHAR(50) NOT NULL UNIQUE
);";

if ($conn->query($sql) === TRUE) {
  echo "Table club_colours created successfully<br>";
} else {
  echo "Error creating table: " . $conn->error;
}

// Insert colours used by Italian clubs
$sql = "INSERT INTO club_colours (colour_name) VALUES
('black'), ('blue'), ('green'), ('yellow'), ('red'),
('white'), ('orange'), ('sky blue'), ('maroon'), ('purple'),
('grey'), ('pink');";

if ($conn->query($sql) === TRUE) {
  echo "Club colours added successfully<br>";
} else {
  echo "Error inserting colours: " . $conn->error;
}

// Insert Serie A Teams (2025-2026 Season)
$sql = "INSERT INTO clubs (name, short_name, division, badge_filename, stadium_name, stadium_city, stadium_capacity, titles_serie_a, titles_coppa_italia, titles_european_cups, colours) VALUES
('Atalanta Bergamasca Calcio', 'Atalanta', 'Serie A', 'atalanta.png', 'Gewiss Stadium', 'Bergamo', 21747, 0, 1, 0, '[\"blue\",\"black\"]'),
('Bologna Football Club 1909', 'Bologna', 'Serie A', 'bologna.png', 'Stadio Renato Dall''Ara', 'Bologna', 36462, 7, 2, 0, '[\"red\",\"blue\"]'),
('Cagliari Calcio', 'Cagliari', 'Serie A', 'cagliari.png', 'Unipol Domus', 'Cagliari', 16233, 0, 0, 0, '[\"red\",\"blue\"]'),
('Como 1907', 'Como', 'Serie A', 'como.png', 'Stadio Giuseppe Sinigaglia', 'Como', 13602, 0, 0, 0, '[\"blue\",\"white\"]'),
('US Cremonese', 'Cremonese', 'Serie A', 'cremonese.png', 'Stadio Giovanni Zini', 'Cremona', 16003, 0, 0, 0, '[\"red\",\"grey\"]'),
('ACF Fiorentina', 'Fiorentina', 'Serie A', 'fiorentina.png', 'Stadio Artemio Franchi', 'Florence', 43147, 2, 6, 1, '[\"purple\",\"white\"]'),
('Genoa Cricket and Football Club', 'Genoa', 'Serie A', 'genoa.png', 'Stadio Luigi Ferraris', 'Genoa', 36599, 9, 1, 0, '[\"red\",\"blue\"]'),
('Hellas Verona Football Club', 'Hellas Verona', 'Serie A', 'hellasverona.png', 'Stadio Marcantonio Bentegodi', 'Verona', 39211, 0, 0, 0, '[\"yellow\",\"blue\"]'),
('Football Club Internazionale Milano', 'Inter', 'Serie A', 'intermilan.png', 'Stadio Giuseppe Meazza', 'Milan', 75817, 20, 9, 3, '[\"blue\",\"black\"]'),
('Juventus Football Club', 'Juventus', 'Serie A', 'juventus.png', 'Allianz Stadium', 'Turin', 41507, 36, 15, 2, '[\"black\",\"white\"]'),
('Società Sportiva Lazio', 'Lazio', 'Serie A', 'lazio.png', 'Stadio Olimpico', 'Rome', 70634, 2, 7, 1, '[\"sky blue\",\"white\"]'),
('Unione Sportiva Lecce', 'Lecce', 'Serie A', 'lecce.png', 'Stadio Via del Mare', 'Lecce', 31533, 0, 0, 0, '[\"yellow\",\"red\"]'),
('Associazione Calcio Milan', 'Milan', 'Serie A', 'acmilan.png', 'Stadio Giuseppe Meazza', 'Milan', 75817, 19, 5, 7, '[\"red\",\"black\"]'),
('Società Sportiva Calcio Napoli', 'Napoli', 'Serie A', 'napoli.png', 'Stadio Diego Armando Maradona', 'Naples', 54726, 4, 6, 1, '[\"blue\",\"white\"]'),
('Parma Calcio 1913', 'Parma', 'Serie A', 'parma.png', 'Stadio Ennio Tardini', 'Parma', 27906, 0, 3, 3, '[\"yellow\",\"blue\"]'),
('Pisa Sporting Club', 'Pisa', 'Serie A', 'pisa.png', 'Stadio Romeo Anconetani', 'Pisa', 10200, 0, 0, 0, '[\"blue\"]'),
('Associazione Sportiva Roma', 'Roma', 'Serie A', 'asroma.png', 'Stadio Olimpico', 'Rome', 70634, 3, 9, 1, '[\"red\",\"yellow\"]'),
('Unione Sportiva Sassuolo Calcio', 'Sassuolo', 'Serie A', 'sassuolo.png', 'Mapei Stadium', 'Reggio Emilia', 23717, 0, 0, 0, '[\"green\",\"black\"]'),
('Torino Football Club', 'Torino', 'Serie A', 'torino.png', 'Stadio Olimpico Grande Torino', 'Turin', 27994, 7, 5, 0, '[\"maroon\",\"white\"]'),
('Udinese Calcio', 'Udinese', 'Serie A', 'udinese.png', 'Bluenergy Stadium', 'Udine', 25144, 0, 0, 0, '[\"black\",\"white\"]');";

if ($conn->query($sql) === TRUE) {
  echo "Serie A teams added successfully<br>";
} else {
  echo "Error inserting Serie A teams: " . $conn->error;
}

// Insert Serie B Teams (2025-2026 Season)
$sql = "INSERT INTO clubs (name, short_name, division, badge_filename, stadium_name, stadium_city, stadium_capacity, titles_serie_a, titles_serie_b, titles_coppa_italia, colours) VALUES
('Unione Sportiva Avellino 1912', 'Avellino', 'Serie B', 'usavellino.png', 'Stadio Partenio-Adriano Lombardi', 'Avellino', 26308, 0, 0, 0, '[\"green\",\"white\"]'),
('Football Club Bari 1908', 'Bari', 'Serie B', 'bari.png', 'Stadio San Nicola', 'Bari', 58270, 0, 1, 0, '[\"white\",\"red\"]'),
('Carrarese Calcio 1908', 'Carrarese', 'Serie B', 'carrarese.png', 'Stadio dei Marmi', 'Carrara', 4100, 0, 0, 0, '[\"yellow\",\"blue\"]'),
('Calcio Catanzaro', 'Catanzaro', 'Serie B', 'uscatanzaro.png', 'Stadio Nicola Ceravolo', 'Catanzaro', 14650, 0, 0, 0, '[\"red\",\"yellow\"]'),
('Cesena Football Club', 'Cesena', 'Serie B', 'cesena.png', 'Stadio Dino Manuzzi', 'Cesena', 23860, 0, 1, 0, '[\"black\",\"white\"]'),
('Empoli Football Club', 'Empoli', 'Serie B', 'empoli.png', 'Stadio Carlo Castellani', 'Empoli', 16800, 0, 0, 0, '[\"blue\"]'),
('Frosinone Calcio', 'Frosinone', 'Serie B', 'frosinone.png', 'Stadio Benito Stirpe', 'Frosinone', 16227, 0, 0, 0, '[\"yellow\",\"blue\"]'),
('Calcio Juve Stabia', 'Juve Stabia', 'Serie B', 'juvestabia.png', 'Stadio Romeo Menti', 'Castellammare di Stabia', 12800, 0, 0, 0, '[\"yellow\",\"blue\"]'),
('Mantova 1911', 'Mantova', 'Serie B', 'mantova.png', 'Stadio Danilo Martelli', 'Mantova', 14844, 0, 1, 0, '[\"white\",\"red\"]'),
('Modena Football Club', 'Modena', 'Serie B', 'modena.png', 'Stadio Alberto Braglia', 'Modena', 21092, 0, 0, 0, '[\"yellow\",\"blue\"]'),
('Associazione Calcio Monza', 'Monza', 'Serie B', 'monza.png', 'U-Power Stadium', 'Monza', 18568, 0, 0, 0, '[\"red\",\"white\"]'),
('Calcio Padova', 'Padova', 'Serie B', 'padova.png', 'Stadio Euganeo', 'Padova', 32420, 0, 1, 0, '[\"white\",\"red\"]'),
('Calcio Palermo', 'Palermo', 'Serie B', 'palermo.png', 'Stadio Renzo Barbera', 'Palermo', 36349, 0, 0, 0, '[\"pink\",\"black\"]'),
('Pescara Calcio', 'Pescara', 'Serie B', 'pescara.png', 'Stadio Adriatico', 'Pescara', 20515, 0, 1, 0, '[\"white\",\"blue\"]'),
('Reggiana', 'Reggiana', 'Serie B', 'reggiana.png', 'Stadio Città del Tricolore', 'Reggio Emilia', 21525, 0, 0, 0, '[\"red\",\"white\"]'),
('Unione Calcio Sampdoria', 'Sampdoria', 'Serie B', 'sampdoria.png', 'Stadio Luigi Ferraris', 'Genoa', 36599, 1, 0, 4, '[\"blue\",\"white\",\"red\",\"black\"]'),
('Spezia Calcio', 'Spezia', 'Serie B', 'spezia.png', 'Stadio Alberto Picco', 'La Spezia', 10336, 0, 3, 0, '[\"white\",\"black\"]'),
('Football Club Südtirol', 'Südtirol', 'Serie B', 'sudtirol.png', 'Stadio Druso', 'Bolzano', 5000, 0, 0, 0, '[\"white\",\"red\"]'),
('Venezia Football Club', 'Venezia', 'Serie B', 'venezia.png', 'Stadio Pier Luigi Penzo', 'Venice', 11150, 0, 0, 0, '[\"orange\",\"green\",\"black\"]'),
('Virtus Entella', 'Entella', 'Serie B', 'virtusentella.png', 'Stadio Comunale', 'Chiavari', 3932, 0, 0, 0, '[\"blue\"]');";

if ($conn->query($sql) === TRUE) {
  echo "Serie B teams added successfully<br>";
} else {
  echo "Error inserting Serie B teams: " . $conn->error;
}

// Insert Serie C North Teams (2025-2026 Season)
$sql = "INSERT INTO clubs (name, short_name, division, badge_filename, stadium_name, stadium_city, stadium_capacity, titles_serie_a, titles_serie_b, titles_coppa_italia, colours) VALUES
('Albinoleffe UC', 'Albinoleffe', 'Serie C North', 'albinoleffe.png', 'Stadio Città di Gorgonzola', 'Gorgonzola', 3590, 0, 0, 0, '[\"blue\",\"white\"]'),
('Alcione Milano', 'Alcione', 'Serie C North', 'alcione.png', 'Arena Civica', 'Milan', 10000, 0, 0, 0, '[\"orange\",\"black\"]'),
('Arzignano Valchiampo', 'Arzignano', 'Serie C North', 'arzignano.png', 'Stadio Tommaso Dal Molin', 'Arzignano', 2500, 0, 0, 0, '[\"yellow\",\"green\"]'),
('Cittadella', 'Cittadella', 'Serie C North', 'cittadella.png', 'Stadio Pier Cesare Tombolato', 'Cittadella', 7623, 0, 0, 0, '[\"red\",\"blue\"]'),
('Dolomiti Bellunesi', 'Dolomiti Bellunesi', 'Serie C North', 'dolomitibellunesi.png', 'Stadio Comunale Nevegal', 'Belluno', 2550, 0, 0, 0, '[\"yellow\",\"blue\"]'),
('Giana Erminio', 'Giana Erminio', 'Serie C North', 'gianaerminio.png', 'Stadio Città di Gorgonzola', 'Gorgonzola', 3590, 0, 0, 0, '[\"blue\",\"white\"]'),
('Inter Milan U23', 'Inter U23', 'Serie C North', 'intermilanu23.png', 'Stadio Breda', 'Sesto San Giovanni', 4568, 0, 0, 0, '[\"blue\",\"black\"]'),
('Lecco 1912', 'Lecco', 'Serie C North', 'lecco.png', 'Stadio Mario Rigamonti', 'Lecco', 4997, 0, 0, 0, '[\"blue\",\"white\"]'),
('Lumezzane VGZ', 'Lumezzane', 'Serie C North', 'lumezzane.png', 'Stadio Nuovo Tullio Saleri', 'Lumezzane', 4150, 0, 0, 0, '[\"red\",\"white\"]'),
('Novara Calcio', 'Novara', 'Serie C North', 'novara.png', 'Stadio Silvio Piola', 'Novara', 17875, 0, 0, 0, '[\"blue\",\"white\"]'),
('Ospitaletto Calcio', 'Ospitaletto', 'Serie C North', 'ospitaletto.png', 'Stadio Comunale', 'Ospitaletto', 1200, 0, 0, 0, '[\"blue\",\"white\"]'),
('Pergolettese', 'Pergolettese', 'Serie C North', 'pergolettese.png', 'Stadio Giuseppe Voltini', 'Crema', 4095, 0, 0, 0, '[\"yellow\",\"blue\"]'),
('Pro Patria', 'Pro Patria', 'Serie C North', 'propatria.png', 'Stadio Carlo Speroni', 'Busto Arsizio', 3990, 0, 0, 0, '[\"white\",\"blue\"]'),
('Pro Vercelli', 'Pro Vercelli', 'Serie C North', 'provercelli.png', 'Stadio Silvio Piola', 'Vercelli', 5500, 7, 0, 1, '[\"white\"]'),
('Renate', 'Renate', 'Serie C North', 'renate.png', 'Stadio Città di Meda', 'Meda', 4000, 0, 0, 0, '[\"blue\",\"white\"]'),
('Trento Calcio 1921', 'Trento', 'Serie C North', 'trento.png', 'Stadio Briamasco', 'Trento', 4360, 0, 0, 0, '[\"yellow\",\"blue\"]'),
('Triestina', 'Triestina', 'Serie C North', 'triestina.png', 'Stadio Nereo Rocco', 'Trieste', 32454, 0, 0, 0, '[\"red\",\"white\"]'),
('Union Brescia', 'Brescia', 'Serie C North', 'brescia.png', 'Stadio Mario Rigamonti', 'Brescia', 16743, 0, 1, 0, '[\"blue\",\"white\"]'),
('Vicenza Calcio', 'Vicenza', 'Serie C North', 'vicenza.png', 'Stadio Romeo Menti', 'Vicenza', 12000, 0, 0, 0, '[\"red\",\"white\"]'),
('Virtus Verona', 'Virtus Verona', 'Serie C North', 'virtusverona.png', 'Stadio Marc Antonio Bentegodi', 'Verona', 39211, 0, 0, 0, '[\"red\",\"blue\"]');";

if ($conn->query($sql) === TRUE) {
  echo "Serie C North teams added successfully<br>";
} else {
  echo "Error inserting Serie C North teams: " . $conn->error;
}

// Insert Serie C Centre Teams (2025-2026 Season)
$sql = "INSERT INTO clubs (name, short_name, division, badge_filename, stadium_name, stadium_city, stadium_capacity, titles_serie_a, titles_serie_b, titles_coppa_italia, colours) VALUES
('Arezzo', 'Arezzo', 'Serie C Centre', 'arezzo.png', 'Stadio Città di Arezzo', 'Arezzo', 13128, 0, 0, 0, '[\"red\"]'),
('Ascoli Calcio 1898', 'Ascoli', 'Serie C Centre', 'ascoli.png', 'Stadio Cino e Lillo Del Duca', 'Ascoli Piceno', 12547, 0, 1, 0, '[\"black\",\"white\"]'),
('Bra Calcio', 'Bra', 'Serie C Centre', 'bra.png', 'Stadio Attilio Bravi', 'Bra', 3500, 0, 0, 0, '[\"red\",\"white\"]'),
('Campobasso FC', 'Campobasso', 'Serie C Centre', 'campobasso.png', 'Stadio Nuovo Romagnoli', 'Campobasso', 25000, 0, 0, 0, '[\"red\",\"blue\"]'),
('Carpi FC 1909', 'Carpi', 'Serie C Centre', 'carpi.png', 'Stadio Sandro Cabassi', 'Carpi', 4144, 0, 0, 0, '[\"white\",\"red\"]'),
('Forlì FC', 'Forlì', 'Serie C Centre', 'forli.png', 'Stadio Tullo Morgagni', 'Forlì', 4500, 0, 0, 0, '[\"red\",\"white\"]'),
('Gubbio', 'Gubbio', 'Serie C Centre', 'gubbio.png', 'Stadio Pietro Barbetti', 'Gubbio', 5300, 0, 0, 0, '[\"blue\",\"red\"]'),
('Guidonia Montecelio', 'Guidonia', 'Serie C Centre', 'guidonia.png', 'Stadio Comunale', 'Guidonia', 2500, 0, 0, 0, '[\"blue\",\"white\"]'),
('Juventus Next Gen', 'Juve U23', 'Serie C Centre', 'juventusnextgen.png', 'Stadio Giuseppe Moccagatta', 'Alessandria', 5926, 0, 0, 0, '[\"black\",\"white\"]'),
('Livorno Calcio', 'Livorno', 'Serie C Centre', 'livorno.png', 'Stadio Armando Picchi', 'Livorno', 19238, 0, 0, 0, '[\"red\"]'),
('Perugia Calcio', 'Perugia', 'Serie C Centre', 'perugia.png', 'Stadio Renato Curi', 'Perugia', 23625, 0, 1, 0, '[\"red\",\"white\"]'),
('Pianese', 'Pianese', 'Serie C Centre', 'pianese.png', 'Stadio Comunale', 'Piancastagnaio', 2000, 0, 0, 0, '[\"white\",\"black\"]'),
('Pineto Calcio', 'Pineto', 'Serie C Centre', 'pineto.png', 'Stadio Davide Pavone', 'Pineto', 4100, 0, 0, 0, '[\"red\",\"white\"]'),
('Pontedera', 'Pontedera', 'Serie C Centre', 'pontedera.png', 'Stadio Ettore Mannucci', 'Pontedera', 2300, 0, 0, 0, '[\"red\",\"blue\"]'),
('Ravenna FC', 'Ravenna', 'Serie C Centre', 'ravenna.png', 'Stadio Bruno Benelli', 'Ravenna', 12020, 0, 0, 0, '[\"yellow\",\"red\"]'),
('Rimini FC', 'Rimini', 'Serie C Centre', 'rimini.png', 'Stadio Romeo Neri', 'Rimini', 9768, 0, 0, 0, '[\"white\",\"red\"]'),
('Sambenedettese', 'Samb', 'Serie C Centre', 'sambenedettese.png', 'Stadio Riviera delle Palme', 'San Benedetto del Tronto', 13708, 0, 0, 0, '[\"red\",\"blue\"]'),
('Ternana Calcio', 'Ternana', 'Serie C Centre', 'ternana.png', 'Stadio Libero Liberati', 'Terni', 22000, 0, 0, 0, '[\"red\",\"green\"]'),
('Torres 1903', 'Torres', 'Serie C Centre', 'torres.png', 'Stadio Vanni Sanna', 'Sassari', 7000, 0, 0, 0, '[\"red\",\"blue\"]'),
('Vis Pesaro', 'Vis Pesaro', 'Serie C Centre', 'vispesaro.png', 'Stadio Tonino Benelli', 'Pesaro', 7607, 0, 0, 0, '[\"red\",\"white\"]');";

if ($conn->query($sql) === TRUE) {
  echo "Serie C Centre teams added successfully<br>";
} else {
  echo "Error inserting Serie C Centre teams: " . $conn->error;
}

// Insert Serie C South Teams (2025-2026 Season)
$sql = "INSERT INTO clubs (name, short_name, division, badge_filename, stadium_name, stadium_city, stadium_capacity, titles_serie_a, titles_serie_b, titles_coppa_italia, colours) VALUES
('Atalanta U23', 'Atalanta U23', 'Serie C South', 'atalantau23.png', 'Stadio Comunale di Caravaggio', 'Caravaggio', 1400, 0, 0, 0, '[\"blue\",\"black\"]'),
('Audace Cerignola', 'Cerignola', 'Serie C South', 'cerignola.png', 'Stadio Domenico Monterisi', 'Cerignola', 10200, 0, 0, 0, '[\"yellow\",\"red\"]'),
('Benevento Calcio', 'Benevento', 'Serie C South', 'benevento.png', 'Stadio Ciro Vigorito', 'Benevento', 16867, 0, 1, 0, '[\"yellow\",\"red\"]'),
('Casarano Calcio', 'Casarano', 'Serie C South', 'casarano.png', 'Stadio Giuseppe Capozza', 'Casarano', 7200, 0, 0, 0, '[\"red\",\"blue\"]'),
('Casertana FC', 'Casertana', 'Serie C South', 'casertana.png', 'Stadio Alberto Pinto', 'Caserta', 12000, 0, 0, 0, '[\"blue\"]'),
('Catania FC', 'Catania', 'Serie C South', 'catania.png', 'Stadio Angelo Massimino', 'Catania', 23420, 0, 2, 0, '[\"red\",\"blue\"]'),
('Cavese 1919', 'Cavese', 'Serie C South', 'cavese.png', 'Stadio Simonetta Lamberti', 'Cava de Tirreni', 5420, 0, 0, 0, '[\"blue\",\"white\"]'),
('Cosenza Calcio', 'Cosenza', 'Serie C South', 'cosenza.png', 'Stadio San Vito-Gigi Marulla', 'Cosenza', 20987, 0, 0, 0, '[\"red\",\"blue\"]'),
('Crotone', 'Crotone', 'Serie C South', 'crotone.png', 'Stadio Ezio Scida', 'Crotone', 16547, 0, 0, 0, '[\"red\",\"blue\"]'),
('Foggia Calcio', 'Foggia', 'Serie C South', 'foggia.png', 'Stadio Pino Zaccheria', 'Foggia', 25085, 0, 0, 0, '[\"red\",\"black\"]'),
('Giugliano Calcio', 'Giugliano', 'Serie C South', 'giugliano.png', 'Stadio Alberto De Cristofaro', 'Giugliano', 3000, 0, 0, 0, '[\"yellow\",\"blue\"]'),
('Latina Calcio', 'Latina', 'Serie C South', 'latina.png', 'Stadio Domenico Francioni', 'Latina', 9398, 0, 0, 0, '[\"red\",\"black\"]'),
('Monopoli', 'Monopoli', 'Serie C South', 'monopoli.png', 'Stadio Vito Simone Veneziani', 'Monopoli', 6250, 0, 0, 0, '[\"red\",\"white\"]'),
('Picerno', 'Picerno', 'Serie C South', 'picerno.png', 'Stadio Donato Curcio', 'Picerno', 3000, 0, 0, 0, '[\"red\",\"blue\"]'),
('Potenza Calcio', 'Potenza', 'Serie C South', 'potenza.png', 'Stadio Alfredo Viviani', 'Potenza', 5000, 0, 0, 0, '[\"red\",\"blue\"]'),
('US Salernitana 1919', 'Salernitana', 'Serie C South', 'salernitana.png', 'Stadio Arechi', 'Salerno', 37800, 0, 0, 0, '[\"red\",\"blue\"]'),
('Siracusa Calcio', 'Siracusa', 'Serie C South', 'siracusa.png', 'Stadio Nicola De Simone', 'Syracuse', 6700, 0, 0, 0, '[\"blue\",\"white\"]'),
('Sorrento Calcio', 'Sorrento', 'Serie C South', 'sorrento.png', 'Stadio Italia', 'Sorrento', 3700, 0, 0, 0, '[\"red\",\"black\"]'),
('Team Altamura', 'Altamura', 'Serie C South', 'altamura.png', 'Stadio Tonino D\'Angelo', 'Altamura', 7500, 0, 0, 0, '[\"white\",\"red\"]'),
('Trapani Calcio', 'Trapani', 'Serie C South', 'trapani.png', 'Stadio Provinciale', 'Trapani', 7000, 0, 0, 0, '[\"red\",\"white\"]');";

if ($conn->query($sql) === TRUE) {
  echo "Serie C South teams added successfully<br>";
} else {
  echo "Error inserting Serie C South teams: " . $conn->error;
}

$conn->close();

?>
</body>
</html>