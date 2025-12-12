CREATE TABLE IF NOT EXISTS matches (
    id INT PRIMARY KEY,
    date DATETIME NOT NULL,
    status VARCHAR(50),
    home_team_id INT,
    away_team_id INT,
    home_score INT,
    away_score INT,
    league_id INT,
    season INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (home_team_id) REFERENCES teams(id) ON DELETE CASCADE,
    FOREIGN KEY (away_team_id) REFERENCES teams(id) ON DELETE CASCADE
);
