CREATE TABLE IF NOT EXISTS players (
    id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    firstname VARCHAR(255),
    lastname VARCHAR(255),
    age INT,
    nationality VARCHAR(255),
    height VARCHAR(50),
    weight VARCHAR(50),
    photo VARCHAR(255),
    team_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE SET NULL
);
