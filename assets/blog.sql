CREATE TABLE admins (
  id int(11) NOT NULL,
  username varchar(50) NOT NULL,
  password_hash varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

CREATE TABLE password_resets (
  id int(11) NOT NULL,
  email varchar(255) DEFAULT NULL,
  token_hash varchar(255) DEFAULT NULL,
  expires_at datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

CREATE TABLE posts (
  id int(11) NOT NULL,
  title varchar(255) NOT NULL,
  post_image varchar(255) DEFAULT 'default.png',
  content text NOT NULL,
  created_at timestamp NULL DEFAULT current_timestamp(),
  user_id int(11) DEFAULT NULL,
  status enum('pending','published') DEFAULT 'pending'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- FULL DATA INSERT (shortened here for readability — but structure same)
INSERT INTO posts (id, title, post_image, content, created_at, user_id, status) VALUES
(1, 'Top IT Skills You Must Learn in 2026 to Get a High-Paying Tech Job', '6975e61715e2e.png', 'Full Stack, AI, Cloud, Security content...', '2026-01-25 09:44:55', 1, 'published'),
(2, 'How Tech Companies Are Hiring in 2026: New Rules, New Skills', '6975e9988758e.png', 'Hiring trends, AI recruitment, skill-based hiring...', '2026-01-25 09:59:52', 1, 'published'),
(3, 'Hackathons in 2026: Why Tech Companies Are Betting Big', '6975ec147cc86.png', 'Hackathon benefits, hiring impact...', '2026-01-25 10:10:28', 1, 'published'),
(11, 'TCS iON Career Edge – Young Professional', '69a5e6b9b1a0d.png', 'Course details, benefits...', '2026-01-30 17:42:47', 1, 'published'),
(12, 'Top Development Skills You Must Master in 2026', '69a5e8b480cdb.png', 'Full stack, AI, DevOps...', '2026-03-02 19:44:53', 1, 'published');

-- --------------------------------------------------------

CREATE TABLE users (
  ID int(11) NOT NULL,
  USER_NAME varchar(100) NOT NULL,
  EMAIL varchar(100) NOT NULL,
  PASSWORD varchar(255) NOT NULL,
  PROFILE_IMG varchar(255) DEFAULT 'default.png',
  VERIFICATION_CODE int(11) DEFAULT NULL,
  VERIFICATION_STATUS enum('Success','Failed') DEFAULT 'Failed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

ALTER TABLE admins
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY username (username);

ALTER TABLE password_resets
  ADD PRIMARY KEY (id);

ALTER TABLE posts
  ADD PRIMARY KEY (id);

ALTER TABLE users
  ADD PRIMARY KEY (ID);

-- AUTO_INCREMENT

ALTER TABLE admins
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE password_resets
  MODIFY id int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE posts
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

ALTER TABLE users
  MODIFY ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

COMMIT;