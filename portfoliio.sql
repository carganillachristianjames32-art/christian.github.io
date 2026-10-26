CREATE DATABASE IF NOT EXISTS portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portfolio;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(120) NOT NULL,
  description TEXT NOT NULL,
  tech VARCHAR(255) NOT NULL DEFAULT '',
  url VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO projects (title, description, tech, url) VALUES
('Recipe Finder', 'A searchable recipe app with ingredient filters and saved favorites.', 'PHP, MySQL, JavaScript', NULL),
('Task Board', 'A kanban-style task tracker with user accounts and drag-and-drop ordering.', 'PHP, MySQL, CSS', NULL),
('Weather Dashboard', 'Shows a five-day forecast for any city using a public weather API.', 'PHP, cURL, Chart.js', NULL);