
-- Table structure for posts
CREATE TABLE posts (
  id int(11) AUTO_INCREMENT PRIMARY KEY,
  title varchar(255) NOT NULL,
  post_image varchar(255) DEFAULT 'default.png',
  content text NOT NULL,
  created_at date DEFAULT curdate(),
  user_id int(11) DEFAULT NULL,
  status enum('pending','published') DEFAULT 'pending'
);

-- Dumping data for posts
INSERT INTO posts (id, title, content, created_at, user_id) VALUES
(1, 'About Teerthanker Mahaveer University', ' tuyg uhg hbbj hg j bnm bjhjnm j mb', '2025-06-29', 1),
(2, 'A Begginer''s guide to Webdevelopment', 'Intro:\r\nStarting your web development journey can feel overwhelming ...', '2025-06-29', 1),
(3, 'is Webdevelopmet still in demand in 2025', 'With the rise of AI tools and no-code platforms, many are wondering ...', '2025-06-29', 1),
(4, 'Lava Prowatch   X review', 'Sturdy yet lightweight metal alloy body, elegant design ...', '2025-08-18', 5);

-- Auto-increment for table posts
ALTER TABLE posts MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;


//ADD post image column to posts table

