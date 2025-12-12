CREATE TABLE IF NOT EXISTS bets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    match_id INT NOT NULL,
    user_id INT NOT NULL,
    home_score INT,
    away_score INT,
    winner_team_id INT,
    goal_difference INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (match_id) REFERENCES matches(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (winner_team_id) REFERENCES teams(id) ON DELETE SET NULL
);