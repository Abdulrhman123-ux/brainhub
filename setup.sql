-- ============================================
-- BrainHub v2 — Full Database Setup
-- ============================================
CREATE DATABASE IF NOT EXISTS brainhub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE brainhub;

DROP TABLE IF EXISTS scores;
DROP TABLE IF EXISTS daily_quests;
DROP TABLE IF EXISTS user_progress;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS games;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS admins;
DROP TABLE IF EXISTS feedback;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    icon VARCHAR(10) NOT NULL,
    description TEXT,
    color VARCHAR(50) DEFAULT '#6c63ff'
);

CREATE TABLE games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    description TEXT,
    status ENUM('active','draft') DEFAULT 'active',
    plays INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(80) NOT NULL UNIQUE,
    email VARCHAR(200) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(10) DEFAULT '🧠',
    total_score INT DEFAULT 0,
    games_played INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    difficulty ENUM('Easy','Medium','Hard') NOT NULL,
    best_score INT DEFAULT 0,
    times_played INT DEFAULT 0,
    last_played TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_progress (user_id, game_id, difficulty),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE
);

CREATE TABLE scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    game_id INT NOT NULL,
    difficulty ENUM('Easy','Medium','Hard') DEFAULT 'Medium',
    score INT NOT NULL,
    player_name VARCHAR(100) DEFAULT 'Anonymous',
    played_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE daily_quests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quest_date DATE NOT NULL,
    game_id INT NOT NULL,
    quest_title VARCHAR(200),
    quest_type VARCHAR(50) DEFAULT 'daily',
    FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE
);

CREATE TABLE feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(200),
    message TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

INSERT INTO categories (name, slug, icon, description, color) VALUES
('Memory Games',   'memory',   '🧠', 'Train your working memory by matching cards and recalling patterns.', '#6c63ff'),
('Reaction Games', 'reaction', '⚡', 'Test how fast your brain processes and responds to stimuli.',        '#f7931e'),
('Sequence Games', 'sequence', '🎵', 'Remember and repeat sequences — classic Simon-style challenges.',    '#00b4d8'),
('Logic & Numbers','logic',    '🔢', 'Sharpen your mind with number memory and pattern recognition.',      '#38b000');

INSERT INTO games (category_id, title, slug, description, plays) VALUES
(1, 'Card Match',  'card-match',  'Flip cards and find matching pairs. Classic concentration game.',         5823),
(1, 'Grid Memory', 'grid-memory', 'Watch a grid light up, then reproduce the exact pattern from memory.',   4120),
(1, 'Word Flash',  'word-flash',  'Words flash on screen one by one — memorize them all and type them back.',3980),
(1, 'Face Memory', 'face-memory', 'Memorize which face is in which position on the grid.',                  2944),
(2, 'Reaction Time',   'reaction-time',  'How fast can you click when the screen changes? Measure your reaction speed.', 9201),
(2, 'Color Rush',      'color-rush',     'Click only the correct color as they flash across the screen.',               5512),
(2, 'Aim Trainer',     'aim-trainer',    'Click appearing targets as quickly as possible.',                             4870),
(2, 'Don''t Click Red','dont-click-red', 'Click green tiles only. One wrong click ends the game.',                      3985),
(3, 'Simon Says',      'simon-says',     'Remember and repeat the growing color sequence.',                              8830),
(3, 'Number Sequence', 'number-sequence','Watch numbers appear one by one, then recall them all in order.',             4200),
(3, 'Pattern Repeat',  'pattern-repeat', 'Repeat the highlighted square pattern on the grid.',                          3760),
(3, 'Rhythm Tap',      'rhythm-tap',     'Tap in the same rhythm that plays back to you.',                              2980),
(4, 'Number Guess','number-guess','Guess the secret number using hot/cold hints.',  7100),
(4, 'Digit Span',  'digit-span',  'Remember an ever-growing sequence of digits.',   5340),
(4, 'Math Sprint', 'math-sprint', 'Solve simple math problems as fast as you can.', 6200),
(4, 'Count Dots',  'count-dots',  'How many dots flashed? Count quickly and accurately.', 3650);

INSERT INTO admins (username, password) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
