-- Fichier SQL de test pour la méthode findTop10ByScore()
-- Compatible avec votre structure de table Utilisateur après migration

-- Supprimer les données existantes pour un test propre
DELETE FROM Utilisateur;

-- Réinitialiser l'auto-increment
ALTER TABLE Utilisateur AUTO_INCREMENT = 1;

-- Insérer 15 utilisateurs avec des scores désordonnés pour tester le tri
INSERT INTO Utilisateur (
    username, 
    mail, 
    mot_de_passe, 
    score_total, 
    creation_date, 
    last_co, 
    is_verified, 
    roles,
    last_try_date
) VALUES 
-- Utilisateurs avec scores élevés (devraient être dans le top 10)
('flag_master', 'user15@ctf.com', '$2y$13$password_hash', 4200, '2024-01-15 10:00:00', NOW(), 1, '["ROLE_USER"]', NULL),
('exploit_king', 'user14@ctf.com', '$2y$13$password_hash', 3800, '2024-01-14 09:30:00', NOW(), 1, '["ROLE_USER"]', NULL),
('crypto_wizard', 'user13@ctf.com', '$2y$13$password_hash', 3600, '2024-01-13 11:15:00', NOW(), 1, '["ROLE_USER"]', NULL),
('reverse_engineer', 'user12@ctf.com', '$2y$13$password_hash', 3500, '2024-01-12 14:20:00', NOW(), 1, '["ROLE_USER"]', NULL),
('web_hacker', 'user11@ctf.com', '$2y$13$password_hash', 3200, '2024-01-11 16:45:00', NOW(), 1, '["ROLE_USER"]', NULL),

-- Utilisateurs avec scores moyens (certains dans le top 10)
('binary_ninja', 'user8@ctf.com', '$2y$13$password_hash', 2800, '2024-01-08 08:30:00', NOW(), 1, '["ROLE_USER"]', NULL),
('forensics_expert', 'user9@ctf.com', '$2y$13$password_hash', 2500, '2024-01-09 12:00:00', NOW(), 1, '["ROLE_USER"]', NULL),
('hacker_pro', 'alice@ctf.com', '$2y$13$password_hash', 2500, '2024-01-01 07:00:00', NOW(), 1, '["ROLE_USER"]', NULL), -- Même score mais plus ancien
('pwn_master', 'user10@ctf.com', '$2y$13$password_hash', 2200, '2024-01-10 15:30:00', NOW(), 1, '["ROLE_USER"]', NULL),
('cyber_ninja', 'bob@ctf.com', '$2y$13$password_hash', 2200, '2024-01-02 09:15:00', NOW(), 1, '["ROLE_USER"]', NULL), -- Même score mais plus ancien

-- Utilisateurs avec scores plus faibles (ne devraient PAS être dans le top 10)
('code_breaker', 'charlie@ctf.com', '$2y$13$password_hash', 1800, '2024-01-07 13:45:00', NOW(), 1, '["ROLE_USER"]', NULL),
('security_expert', 'diana@ctf.com', '$2y$13$password_hash', 1650, '2024-01-06 11:20:00', NOW(), 1, '["ROLE_USER"]', NULL),
('flag_hunter', 'eve@ctf.com', '$2y$13$password_hash', 1400, '2024-01-05 10:10:00', NOW(), 1, '["ROLE_USER"]', NULL),
('script_kiddie', 'user4@ctf.com', '$2y$13$password_hash', 1100, '2024-01-04 14:30:00', NOW(), 1, '["ROLE_USER"]', NULL),
('newbie', 'user1@ctf.com', '$2y$13$password_hash', 500, '2024-01-03 16:00:00', NOW(), 1, '["ROLE_USER"]', NULL);

-- Requête de vérification : Top 10 trié par score décroissant
SELECT 
    ROW_NUMBER() OVER (ORDER BY score_total DESC, creation_date ASC) as ranking,
    id,
    username,
    mail,
    score_total,
    creation_date
FROM Utilisateur 
ORDER BY score_total DESC, creation_date ASC 
LIMIT 10;
