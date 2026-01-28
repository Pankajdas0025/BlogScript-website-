

CREATE TABLE password_resets (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255),
  token_hash VARCHAR(255),
  expires_at DATETIME
);
