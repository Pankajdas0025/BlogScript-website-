CREATE TABLE admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL

);
-- -- Dumping data for table `admins`
INSERT INTO admins (username, password_hash) VALUES
('pankaj123', '$2y$10$5eXyBJZfVNYbu2uGGjWAkefxu.wqgvNJ42zWrSNEuaL0uph.rGMs.');