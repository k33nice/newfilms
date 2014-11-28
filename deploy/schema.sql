CREATE TABLE IF NOT EXISTS films(
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Уникальный идентификатор',
  name VARCHAR(200) NOT NULL COMMENT 'Название фильма',
  year int(4) NOT NULL COMMENT 'Год выпуска фильма',
  format ENUM('DVD', 'VHS', 'Blu-Ray') NOT NULL COMMENT 'Формат фильма',
  PRIMARY KEY (id),
  UNIQUE KEY (name)
) ENGINE=InnoDB, DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS actors(
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Уникальный идентификатор',
  name VARCHAR(200) NOT NULL COMMENT 'Имя актера',
  surname VARCHAR(200) COMMENT 'Фамилия актера',
  film_id INT(11) UNSIGNED NOT NULL COMMENT 'Уникальный идентификатор фильма',
  PRIMARY KEY (id),
  UNIQUE KEY (name, surname, film_id),
  FOREIGN KEY (film_id)
  REFERENCES films.films(id)
    ON DELETE CASCADE
) ENGINE=InnoDB, DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;