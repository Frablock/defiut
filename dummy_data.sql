INSERT INTO Utilisateur (nom, prenom, mail, mot_de_passe) VALUES
('Dupont', 'Jean', 'jean.dupont@example.com', 'password1'),
('Martin', 'Marie', 'marie.martin@example.com', 'password2'),
('Durand', 'Paul', 'paul.durand@example.com', 'password3');

INSERT INTO Tag (nom) VALUES
('Aventure'),
('Sport'),
('Culture'),
('Nature'),
('Technologie');

INSERT INTO Indice (contenu) VALUES
('Cherchez près de la fontaine.'),
('Regardez sous le banc.'),
('Demandez au gardien.'),
('Utilisez une carte.'),
('Observez les étoiles.');

INSERT INTO Fichier (nom) VALUES
('carte.png'),
('photo.jpg'),
('document.pdf');

INSERT INTO Defi (nom, description, cle, points_recompense, categorie, difficulte, user_id) VALUES
('Défi 1', 'Trouvez le trésor caché.', 'cle1', 100, 'Aventure', 3, 1),
('Défi 2', 'Complétez le parcours sportif.', 'cle2', 150, 'Sport', 4, 1),
('Défi 3', 'Résolvez l\'énigme culturelle.', 'cle3', 200, 'Culture', 5, 2),
('Défi 4', 'Observez la nature autour de vous.', 'cle4', 120, 'Nature', 2, 2),
('Défi 5', 'Développez une application mobile.', 'cle5', 250, 'Technologie', 5, 3),
('Défi 6', 'Trouvez le trésor caché dans le parc.', 'cle6', 110, 'Aventure', 3, 3),
('Défi 7', 'Complétez le parcours de santé.', 'cle7', 160, 'Sport', 4, 1),
('Défi 8', 'Résolvez l\'énigme historique.', 'cle8', 210, 'Culture', 5, 2),
('Défi 9', 'Observez les oiseaux dans la forêt.', 'cle9', 130, 'Nature', 2, 3),
('Défi 10', 'Créez un site web.', 'cle10', 260, 'Technologie', 5, 1),
('Défi 11', 'Trouvez le trésor caché dans la ville.', 'cle11', 120, 'Aventure', 3, 2),
('Défi 12', 'Complétez le parcours de vélo.', 'cle12', 170, 'Sport', 4, 3),
('Défi 13', 'Résolvez l\'énigme musicale.', 'cle13', 220, 'Culture', 5, 1),
('Défi 14', 'Observez les plantes dans le jardin.', 'cle14', 140, 'Nature', 2, 2),
('Défi 15', 'Développez un jeu vidéo.', 'cle15', 270, 'Technologie', 5, 3),
('Défi 16', 'Trouvez le trésor caché dans la montagne.', 'cle16', 130, 'Aventure', 3, 1);

INSERT INTO Defi_Tag (defi_id, tag_id) VALUES
(1, 1), (1, 4),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 1), (6, 4),
(7, 2),
(8, 3),
(9, 4),
(10, 5),
(11, 1),
(12, 2),
(13, 3),
(14, 4),
(15, 5),
(16, 1);

INSERT INTO Defi_Indice (defi_id, indice_id, ordre) VALUES
(1, 1, 1), (1, 2, 2),
(2, 3, 1),
(3, 4, 1),
(4, 5, 1),
(5, 1, 1), (5, 2, 2),
(6, 3, 1),
(7, 4, 1),
(8, 5, 1),
(9, 1, 1),
(10, 2, 1),
(11, 3, 1),
(12, 4, 1),
(13, 5, 1),
(14, 1, 1),
(15, 2, 1),
(16, 3, 1);

INSERT INTO Defi_Fichier (defi_id, fichier_id) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 1),
(5, 2),
(6, 3),
(7, 1),
(8, 2),
(9, 3),
(10, 1),
(11, 2),
(12, 3),
(13, 1),
(14, 2),
(15, 3),
(16, 1);

INSERT INTO Defi_Utilisateur_Recents (user_id, defi_id) VALUES
(1, 1),
(1, 2),
(2, 3),
(2, 4),
(3, 5),
(3, 6);
