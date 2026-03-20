# 🧠 BrainHub — Brain Training Game Platform

A stunning, fully functional browser-based brain training website built with PHP, MySQL, Bootstrap 5, and vanilla JavaScript.

---

## 📁 Project Structure

```
brainhub/
├── index.php               ← Homepage (hero, categories, featured games, daily quest)
├── category.php            ← Category page (shows all games in a category)
├── game.php                ← Game router (loads the right game by slug)
├── daily-quest.php         ← Today's 3 daily challenges
├── leaderboard.php         ← Top scores per game
├── search.php              ← Search games by keyword
├── about.php               ← About page
├── feedback.php            ← Player feedback form
├── save-score.php          ← POST endpoint to save scores
├── setup.sql               ← ⭐ Run this first to set up database
│
├── includes/
│   ├── db.php              ← PDO database connection
│   ├── header.php          ← Global navbar + HTML head
│   └── footer.php          ← Global footer + scripts
│
├── assets/
│   ├── css/style.css       ← All styles (dark neon theme)
│   └── js/main.js          ← Particles + UI interactions
│
├── games/                  ← 16 fully playable game files
│   ├── card-match.php      🧠 Memory: flip cards, find pairs
│   ├── grid-memory.php     🧠 Memory: memorize lit grid pattern
│   ├── emoji-pairs.php     🧠 Memory: timed emoji matching
│   ├── face-memory.php     🧠 Memory: remember face positions
│   ├── reaction-time.php   ⚡ Reaction: click when green appears
│   ├── color-rush.php      ⚡ Reaction: click the right color fast
│   ├── aim-trainer.php     ⚡ Reaction: click appearing targets
│   ├── dont-click-red.php  ⚡ Reaction: click green only
│   ├── simon-says.php      🎵 Sequence: classic color pattern
│   ├── number-sequence.php 🎵 Sequence: remember digit order
│   ├── pattern-repeat.php  🎵 Sequence: grid color patterns
│   ├── rhythm-tap.php      🎵 Sequence: tap back a rhythm
│   ├── number-guess.php    🔢 Logic: hot/cold number guessing
│   ├── digit-span.php      🔢 Logic: growing digit memory
│   ├── math-sprint.php     🔢 Logic: fast arithmetic with combos
│   └── count-dots.php      🔢 Logic: count flashing dots
│
└── admin/
    ├── login.php           ← Admin login (user: admin / pass: admin123)
    ├── index.php           ← Dashboard with stats & game management
    ├── add-game.php        ← Add new game to database
    ├── edit-game.php       ← Edit existing game
    ├── delete-game.php     ← Delete game
    ├── feedback.php        ← View player feedback
    ├── scores.php          ← View all scores
    └── logout.php          ← Logout
```

---

## ⚙️ Setup Instructions

### 1. Database Setup

Open phpMyAdmin (or MySQL CLI) and run `setup.sql`:

```sql
source /path/to/brainhub/setup.sql
```

Or paste the entire contents of `setup.sql` into phpMyAdmin's SQL tab.

This creates:
- Database: `brainhub`
- Tables: `categories`, `games`, `daily_quests`, `scores`, `feedback`, `admins`
- Inserts all 4 categories + 16 games
- Creates admin account: `admin` / `admin123`

### 2. Place Files

Copy the `brainhub/` folder to your web server root:

```
C:\xampp\htdocs\brainhub\     (Windows/XAMPP)
/var/www/html/brainhub/       (Linux/Apache)
/Applications/XAMPP/htdocs/brainhub/  (Mac/XAMPP)
```

### 3. Configure Database Connection

Open `includes/db.php` and update if needed:

```php
$host = "localhost";
$dbname = "brainhub";
$username = "root";
$password = "";   // ← change if you have a MySQL password
```

### 4. Visit the Site

- **Website:** `http://localhost/brainhub/`
- **Admin Panel:** `http://localhost/brainhub/admin/`
  - Username: `admin`
  - Password: `admin123`

---

## 🎮 Games Overview

### 🧠 Memory Games (4)
| Game | Description | Difficulty |
|------|-------------|------------|
| Card Match | Classic flip-and-match pairs | Easy |
| Grid Memory | Memorize lit grid, reproduce it | Medium |
| Emoji Pairs | Timed matching with combo scores | Easy |
| Face Memory | Remember which face was where | Hard |

### ⚡ Reaction Games (4)
| Game | Description | Difficulty |
|------|-------------|------------|
| Reaction Time | Click when green appears, measure ms | Easy |
| Color Rush | Click the correct color tile | Medium |
| Aim Trainer | Click appearing targets fast | Medium |
| Don't Click Red | Green = safe, red = dead | Hard |

### 🎵 Sequence Games (4)
| Game | Description | Difficulty |
|------|-------------|------------|
| Simon Says | Classic color sequence memory | Medium |
| Number Sequence | Flash digits one by one, type them back | Hard |
| Pattern Repeat | Colored grid pattern memory | Medium |
| Rhythm Tap | Listen to rhythm, tap it back | Easy |

### 🔢 Logic & Numbers (4)
| Game | Description | Difficulty |
|------|-------------|------------|
| Number Guess | Hot/cold number guessing 1–100 | Easy |
| Digit Span | Growing digit span (Human Benchmark style) | Hard |
| Math Sprint | 60-sec arithmetic with combos | Medium |
| Count Dots | Count flashing dots quickly | Medium |

---

## 🎨 Design

- **Theme:** Dark neon — deep navy backgrounds, purple/cyan/orange accents
- **Fonts:** Orbitron (display/headings) + Syne (body)
- **Animations:** Floating particles, glow effects, card flips, smooth transitions
- **Layout:** Bootstrap 5 grid, responsive for mobile/tablet/desktop

---

## 🔐 Admin Panel

- Manage all 16 games (add, edit, delete, activate/draft)
- View dashboard stats (total plays, scores, feedback)
- Read player feedback
- View top scores per game

**Default credentials:** `admin` / `admin123`  
Change the password by running: `php -r "echo password_hash('newpassword', PASSWORD_DEFAULT);"`  
Then update the `admins` table in your DB.

---

## 🔧 Technologies

- **Backend:** PHP 8+ with PDO
- **Database:** MySQL 8
- **Frontend:** HTML5, CSS3, Vanilla JavaScript
- **Framework:** Bootstrap 5.3
- **Fonts:** Google Fonts (Orbitron + Syne)
- **No game engines** — all games are pure JS

---

## ✨ Features

- ✅ 16 fully playable brain games
- ✅ 4 cognitive categories
- ✅ Daily Quest system (3 random games, refreshes daily)
- ✅ Score saving + Leaderboard
- ✅ Search games by title/category
- ✅ Player feedback form
- ✅ Full admin panel
- ✅ Stunning dark neon UI with animations
- ✅ Fully responsive design
- ✅ Clean, readable code — no bloat
