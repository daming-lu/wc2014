DROP TABLE IF EXISTS user_guesses;

/* TABLE : matches */
DROP TABLE IF EXISTS matches;

CREATE TABLE matches(
  match_id SMALLINT NOT NULL AUTO_INCREMENT,
  match_name varchar(10),
  left_team varchar (50),
  right_team varchar (50),
  match_time DATETIME,
  result varchar (10),
  weight SMALLINT,
  PRIMARY KEY(match_id)
);

/* TABLE : users */
DROP TABLE IF EXISTS users;

CREATE TABLE users(
  user_id SMALLINT NOT NULL AUTO_INCREMENT,
  user_name varchar (50) NOT NULL,
  password varchar (50),
  user_email varchar (50),
  last_login_time DATETIME,
  token varchar (50),
  score SMALLINT DEFAULT 0,
  PRIMARY KEY(user_id)
);

/* TABLE : user_guesses */
DROP TABLE IF EXISTS user_guesses;

CREATE TABLE user_guesses(
  user_guess_id SMALLINT NOT NULL AUTO_INCREMENT,
  user_id SMALLINT NOT NULL,
  user_name varchar (50),
  user_score SMALLINT,
  match_1 varchar(10),
  match_1_is_correct BOOLEAN,
  PRIMARY KEY(user_guess_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id)
);

