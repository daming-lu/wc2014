DROP TABLE IF EXISTS users;

CREATE TABLE users
(
  user_id             int(11) NOT NULL AUTO_INCREMENT,
  user_name           varchar(255) NOT NULL,
  password            char(40) NOT NULL,
  user_email          varchar(255) NOT NULL,
  last_login_time     char(11),
  PRIMARY KEY (user_id),
  UNIQUE (user_email)
);


INSERT INTO users (user_name, password, user_email) value ('A', '123', 'a@a.com');
INSERT INTO users (user_name, password, user_email) value ('B', '456', 'b@b.com');