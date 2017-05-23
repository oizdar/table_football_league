CREATE TABLE leagues
(
  id INT UNSIGNED NOT NULL AUTO_INCREMENT
    PRIMARY KEY,
  name VARCHAR(100) NULL,
  description VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `matches`
(
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT
    PRIMARY KEY,
  `league_id` INT UNSIGNED NOT NULL,
  `team_1_player_1` VARCHAR(100) NOT NULL,
  `team_1_player_2` VARCHAR(100) NOT NULL,
  `team_2_player_1` VARCHAR(100) NOT NULL,
  `team_2_player_2` VARCHAR(100) NOT NULL,
  `team_1_score` INT UNSIGNED NULL,
  `team_2_score` INT UNSIGNED NULL,
  FOREIGN KEY (`league_id`) REFERENCES `leagues`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;