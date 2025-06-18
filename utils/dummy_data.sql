-- Ajout des utilisateurs "maker"
INSERT INTO Utilisateur (
        id,
        username,
        mail,
        mot_de_passe,
        is_verified,
        roles,
        score_total
    )
VALUES (
        1,
        'GUERNY Baptiste',
        'baptiste.guerni@example.com',
        'password1',
        0,
        '[]',
        4120
    ),
    (
        2,
        'BOUILLIS Awen',
        'awen.bouillis@example.com',
        'password2',
        0,
        '[]',
        1610
    ),
    (
        3,
        'Patinec François',
        'francois.patinec@defiut.com',
        '$argon2id$v=19$m=16,t=2,p=1$NEt1YXRjSTA4ZFJsaTRxcg$1PWBiMiPnhieLJs/yjfo0A',
        0,
        '["editor"]',
        4980
    ),
    (
        4,
        'AIMÉ Raphaël',
        'aime.raphael@example.com',
        'password3',
        0,
        '[]',
        760
    ),
    (
        5,
        'Pierre Noé',
        'noe.pierre@example.com',
        'password3',
        0,
        '[]',
        2120
    ),
    (
        6,
        'MELLION Jean-Loup',
        'jl.mellion@example.com',
        'password3',
        0,
        '[]',
        2490
    ),
    (
        7,
        'Gabin LEGRAND',
        'gabin.legrand@defiut.com',
        'password3',
        0,
        '[]',
        3340
    ),
    (
        8,
        'Camille LE BRECH',
        'camille.lb@defiut.com',
        'password3',
        0,
        '[]',
        2900
    ),
    (
        9,
        'Gabriel ZENSEN DA SILVA',
        'gabriel.zensen@defiut.com',
        'password3',
        0,
        '[]',
        2830
    );

-- Ajout users pour tester le leaderboard
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

-- Fin test leaderboard

INSERT INTO Tag (id, nom)
VALUES (1, 'Git'),
    (2, 'Commits'),
    (3, 'Java'),
    (4, 'Reverse'),
    (5, 'Base de donnée'),
    (6, 'Optimisation'),
    (7, 'Mathématiques'),
    (8, 'SQL'),
    (9, 'Injection'),
    (10, 'Stéganographie');
INSERT INTO Indice (id, contenu)
VALUES (
        1,
        'Même si un commit a été supprimé avec git reset --hard, il est souvent encore accessible via git reflog. Essaie cette commande pour voir l’historique des actions récentes.'
    ),
    (
        2,
        'Si git log ne montre pas le commit perdu, pense à explorer les objets Git avec git fsck --lost-found ou git rev-list --all. Cela peut révéler des commits qui ne sont plus référencés.'
    ),
    (
        3,
        'Utilise un outil de décompilation comme Ghidra et analyse le fichier'
    ),
    (
        4,
        'Analyse le fichier avec ton outil et regarde bien le code : Un tableau est créé avec des caractères. Puis, on entre dans une boucle qui regarde dans l''ordre chaque caractère du tableau et les ajoute à un objet string (StringBuilder). C''est cet objet String qui constitue le Flag ! Cependant, il y a une variation à l''indice 7 du tableau ! A toi de jouer maintenant !'
    ),
    (5, 'Résout l''Hydre_de_lerne avant'),
    (
        6,
        'Un certain David et un certain Lloyd ont déjà trouvé une solution à ce problème…'
    ),
    (
        7,
        'Les apostrophes et les commentaires SQL fonctionnent de la même manière dans SQLite, peu importe le langage de programmation utilisé.'
    ),
    (
        8,
        'Nos experts suggèrent d''utiliser des outils spécialisés en stéganographie pour cette tâche.'
    );
INSERT INTO Fichier (id, nom)
VALUES (1, 'ctf-git-lost-commit.zip'),
    (2, 'Hydre_de_lerne.zip'),
    (3, 'HydreDemon.zip'),
    (4, 'Le_Pilote_fantome.zip'),
    (5, 'Le_message_invisible.zip');
-- Ajout des défis (avec descriptions Markdown-friendly)

    INSERT INTO Defi (
        id,
        nom,
        description,
        cle,
        points_recompense,
        categorie,
        difficulte,
        user_id
    )
    VALUES 
    (
        1,
        'Le commit perdu',
        '### Contexte  
    Tu travailles sur un devoir **ultra important**.  
    Après une **longue nuit de code**, tu commit une fonctionnalité essentielle…  
    Mais dans un moment de fatigue, tu tapes une mauvaise commande et ton commit *disparaît* !

    ### Objectif  
    Ton professeur te met **-2 sur ta note** si tu rends ton devoir en retard.  
    Le soleil commence à se lever, la **date butoir est à 8h00**.  
    Tu n''as pas le temps de tout recoder.  

    Peux-tu retrouver le commit perdu et **sauver ton année** ?',
        'G1THax3er_3wqC8D',
        215,
        'Collaboration',
        2,
        1
    ),
    (
        2,
        'Hydre de Lerne',
        '### Contexte  
    L''Hydre de Lerne, une **créature mythique**, te barre aujourd''hui la route.  
    Un corps, plusieurs têtes, et une **apparence monstrueuse**.

    ### Objectif  
    Prouve ton courage en faisant face à ce défi.  
    N''aie pas peur de son apparence, **tu es capable** de défaire ce monstre.',
        'its memaaaaaryahoaaaaaa',
        350,
        'Rétroingénierie',
        3,
        2
    ),
    (
        3,
        'HydreDemon',
        '### Avertissement  
    Bon, là, **tu n''es pas capable** de défaire ce monstre.  
    Ou alors, *peut-être que si* ?  
    Tente ta chance… si tu l''oses.',
        'imtheflaaaaag',
        450,
        'Rétroingénierie',
        4,
        3
    ),
    (
        4,
        'Le pilote fantôme',
        '### Mission  
    Un fichier étrange est apparu sur le bureau. Il parle de **pilote fantôme**.  
    Mais qui est-il ? D''où vient-il ?  
    Un peu de fouille s''impose...',
        'phantom_flyer_key',
        300,
        'Forensics',
        2,
        4
    ),
    (
        5,
        'Le message invisible',
        '### Contexte  
    Tu as reçu une image qui semble normale, mais elle **cache un secret**.  
    Certains pixels n''ont pas l''air à leur place...

    ### Objectif  
    **Trouve le message caché** dans cette image.  
    Tu peux utiliser des outils de stéganographie ou tout ce qui te semble utile.',
        'hiddenmsg123',
        180,
        'Stéganographie',
        1,
        5
    );

INSERT INTO Defi_Tag (defi_id, tag_id)
VALUES (1, 1),
    (1, 2),
    (2, 3),
    (3, 3),
    (2, 4),
    (3, 4),
    (4, 5),
    (5, 6),
    (5, 7),
    (6, 8),
    (6, 9),
    (7, 10);
INSERT INTO Defi_Indice (defi_id, indice_id, ordre)
VALUES (1, 1, 1),
    (1, 2, 2),
    (2, 3, 1),
    (2, 4, 2),
    (3, 5, 1),
    (5, 6, 1),
    (6, 7, 1),
    (7, 8, 1);
INSERT INTO Defi_Fichier (defi_id, fichier_id)
VALUES (1, 1),
    (2, 2),
    (3, 3),
    (4, 4),
    (7, 5);
INSERT INTO Defi_Utilisateur_Recents (user_id, defi_id)
VALUES (1, 1),
    (1, 2),
    (2, 3),
    (2, 4),
    (3, 5),
    (3, 6);