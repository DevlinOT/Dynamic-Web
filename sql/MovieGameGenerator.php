<!DOCTYPE html>
<html>

<head>
    <title>Creating Database Table</title>
</head>

<body>

<?php
$conn = new mysqli("localhost", "root", "", NULL, 3306);

$dbname = "movie_game_db";

// Drop database if exists
$sql = "DROP DATABASE IF EXISTS $dbname;";
$conn->query($sql);

// Create database
$sql = "CREATE DATABASE $dbname;";
$conn->query($sql);

$conn->close();

// Reconnect to new DB
$conn = new mysqli("localhost", "root", "", $dbname, 3306);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// --- USERS TABLE ---
$sql = "CREATE TABLE Users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;";
$conn->query($sql);

// --- MOVIES TABLE ---
$sql = "CREATE TABLE Movies (
  movie_id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  release_year YEAR,
  genre VARCHAR(50),
  director VARCHAR(50),
  platform VARCHAR(100),
  image_url VARCHAR(255),
  description TEXT,
  link VARCHAR(100)
) ENGINE=InnoDB;";
$conn->query($sql);

// --- GAMES TABLE ---
$sql = "CREATE TABLE Games (
  game_id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  release_year YEAR,
  genre VARCHAR(50),
  director VARCHAR(50),
  platform VARCHAR(100),
  image_url VARCHAR(255),
  description TEXT,
  link VARCHAR(100)
) ENGINE=InnoDB;";
$conn->query($sql);

// --- REVIEWS TABLE ---
$sql = "CREATE TABLE Reviews (
  review_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  movie_id INT NULL,
  game_id INT NULL,
  rating INT CHECK (rating BETWEEN 1 AND 10),
  review_text TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (movie_id) REFERENCES Movies(movie_id) ON DELETE CASCADE,
  FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE
) ENGINE=InnoDB;";
$conn->query($sql);

// --- WATCHLISTS TABLE ---
$sql = "CREATE TABLE Watchlists (
  watchlist_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  movie_id INT NULL,
  game_id INT NULL,
  added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (movie_id) REFERENCES Movies(movie_id) ON DELETE CASCADE,
  FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE
) ENGINE=InnoDB;";
$conn->query($sql);

// --- INSERT SAMPLE DATA ---

$sql = "INSERT INTO Users (username, email, password_hash)
VALUES
('gamerx', 'gamerx@example.com', 'hash1'),
('filmfan', 'filmfan@example.com', 'hash2'),
('critic42', 'critic42@example.com', 'hash3'),
('moviemaster', 'moviemaster@example.com', 'hash4'),
('devplayer', 'devplayer@example.com', 'hash5');";
$conn->query($sql);

// MOVIES with link identifiers
$sql = "INSERT INTO Movies (title, release_year, genre, director, platform, image_url, description, link) VALUES
('The Last of Us', 2023, 'Drama', 'Craig Mazin', 'HBO', 'https://image.tmdb.org/t/p/w500/uKvVjHNqB5VmOrdxqAt2F7J78ED.jpg', 'TV series adaptation of the PlayStation game.', 'link1'),
('Detective Pikachu', 2019, 'Adventure', 'Rob Letterman', 'Theatrical', 'https://image.tmdb.org/t/p/w500/wgQ7APnFpf1TuviKHXeEe3KnsTV.jpg', 'A detective story set in the Pokémon universe.', 'link2'),
('Tomb Raider', 2018, 'Action', 'Roar Uthaug', 'Theatrical', 'https://image.tmdb.org/t/p/w500/3zrA0pB5XG4jX7o0PZ4Q8xk8Vbr.jpg', 'A reboot of the Lara Croft adventure franchise.', 'link3'),
('Warcraft', 2016, 'Fantasy', 'Duncan Jones', 'Theatrical', 'https://image.tmdb.org/t/p/w500/dXDMXfT0DX8FD8wPw0spIKsPpAe.jpg', 'Humans and orcs clash in Azeroth.', 'link4'),
('Sonic the Hedgehog', 2020, 'Family', 'Jeff Fowler', 'Theatrical', 'https://image.tmdb.org/t/p/w500/aQvJ5WPzZgYVDrxLX4R6cLJCEaQ.jpg', 'A blue hedgehog with super speed battles Dr. Robotnik.', 'link5'),
('Uncharted', 2022, 'Action', 'Ruben Fleischer', 'Theatrical', 'https://image.tmdb.org/t/p/w500/tlZpSxYuBRoVJBOpUrPdQe9FmFq.jpg', 'Treasure hunter Nathan Drake embarks on a global adventure.', 'link6'),
('Resident Evil', 2002, 'Horror', 'Paul W.S. Anderson', 'Theatrical', 'https://image.tmdb.org/t/p/w500/6yA3Y4K4gYRx57AGJ0RjZsN5CFQ.jpg', 'Survivors fight zombies in the Umbrella Corporation lab.', 'link7'),
('Prince of Persia: The Sands of Time', 2010, 'Fantasy', 'Mike Newell', 'Theatrical', 'https://image.tmdb.org/t/p/w500/4ljZcKFZ8dkeG0IfXv6dXw6bY3n.jpg', 'A prince uses a mystical dagger to rewind time.', 'link8'),
('Mortal Kombat', 2021, 'Action', 'Simon McQuoid', 'HBO Max', 'https://image.tmdb.org/t/p/w500/yps0JIP3aV0W5xg3ejYtVwXkKRJ.jpg', 'Earth\'s champions fight in a deadly tournament.', 'link9'),
('Assassin\'s Creed', 2016, 'Sci-Fi', 'Justin Kurzel', 'Theatrical', 'https://image.tmdb.org/t/p/w500/tIKFBxBZhSXpIITiiB5Ws8VGXjt.jpg', 'A man explores genetic memories of an ancestor assassin.', 'link10');";
$conn->query($sql);

// GAMES with same link identifiers
$sql = "INSERT INTO Games (title, release_year, genre, director, platform, image_url, description, link) VALUES
('The Last of Us', 2013, 'Action-Adventure', 'Naughty Dog', 'PlayStation', 'https://cdn.mobygames.com/covers/8373278-the-last-of-us-playstation-3-front-cover.jpg', 'A post-apocalyptic survival story.', 'link1'),
('Pokémon Detective Pikachu', 2016, 'Adventure', 'Creatures Inc.', 'Nintendo 3DS', 'https://cdn.mobygames.com/covers/6255136-pokemon-detective-pikachu-nintendo-3ds-front-cover.jpg', 'Solve mysteries with Pikachu.', 'link2'),
('Tomb Raider', 2013, 'Action-Adventure', 'Crystal Dynamics', 'Multi-platform', 'https://cdn.mobygames.com/covers/4082270-tomb-raider-windows-front-cover.jpg', 'Lara Croft’s origin reboot.', 'link3'),
('Warcraft', 1994, 'Strategy', 'Blizzard Entertainment', 'PC', 'https://cdn.mobygames.com/covers/4917497-warcraft-orcs-humans-dos-front-cover.jpg', 'Fantasy real-time strategy.', 'link4'),
('Sonic the Hedgehog', 1991, 'Platformer', 'Sega', 'Genesis', 'https://cdn.mobygames.com/covers/6171273-sonic-the-hedgehog-sega-genesis-front-cover.jpg', 'Fast-paced platforming.', 'link5'),
('Uncharted 4: A Thief’s End', 2016, 'Action-Adventure', 'Naughty Dog', 'PlayStation', 'https://cdn.mobygames.com/covers/6302621-uncharted-4-a-thiefs-end-playstation-4-front-cover.jpg', 'Nathan Drake’s final adventure.', 'link6'),
('Resident Evil', 1996, 'Horror', 'Capcom', 'PlayStation', 'https://cdn.mobygames.com/covers/6662048-resident-evil-playstation-front-cover.jpg', 'Survive the zombie outbreak.', 'link7'),
('Prince of Persia: The Sands of Time', 2003, 'Action', 'Ubisoft Montreal', 'Multi-platform', 'https://cdn.mobygames.com/covers/3601328-prince-of-persia-the-sands-of-time-windows-front-cover.jpg', 'Use time to solve puzzles and fight enemies.', 'link8'),
('Mortal Kombat', 1992, 'Fighting', 'Midway Games', 'Arcade', 'https://cdn.mobygames.com/covers/6813791-mortal-kombat-arcade-front-cover.jpg', 'Brutal fighting tournament.', 'link9'),
('Assassin’s Creed II', 2009, 'Action-Adventure', 'Ubisoft', 'Multi-platform', 'https://cdn.mobygames.com/covers/7766067-assassins-creed-ii-windows-front-cover.jpg', 'Ezio’s journey through Renaissance Italy.', 'link10');";
$conn->query($sql);

$conn->close();
echo "All tables and entries created successfully with link field!";
?>

</body>
</html>
