INSERT INTO Utilisateur (
        id,
        nom,
        prenom,
        mail,
        mot_de_passe,
        is_verified,
        roles
    )
VALUES (
        1,
        'GUERNY',
        'Baptiste',
        'baptiste.guerni@example.com',
        'password1',
        0,
        '[]'
    ),
    (
        2,
        'BOUILLIS',
        'Awen',
        'awen.bouillis@example.com',
        'password2',
        0,
        '[]'
    ),
    (
        3,
        'Patinec',
        'François',
        'francois.patinec@example.com',
        'password2',
        0,
        '[]'
    ),
    (
        4,
        'AIMÉ',
        'Raphaël',
        'aime.raphael@example.com',
        'password3',
        0,
        '[]'
    ),
    (
        5,
        'Pierre',
        'Noé',
        'noe.pierre@example.com',
        'password3',
        0,
        '[]'
    ),
    (
        6,
        'MELLION',
        'Jean-Loup',
        'jl.mellion@example.com',
        'password3',
        0,
        '[]'
    );
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
        'Même si un commit a été supprimé avec git reset --hard, il est
souvent encore accessible via git reflog. Essaie cette commande pour voir
l’historique des actions récentes.'
    ),
    (
        2,
        'Si git log ne montre pas le commit perdu, pense à explorer les objets
Git avec git fsck --lost-found ou git rev-list --all. Cela peut révéler des
commits qui ne sont plus référencés.'
    ),
    (
        3,
        'Utilise un outil de décompilation comme Ghidra et analyse le fichier'
    ),
    (
        4,
        'Analyse le fichier avec ton outil et regarde bien le code : Un tableau est créé
avec des caractères. Puis, on entre dans une boucle qui regarde dans l\'ordre
chaque caractère du tableau et les ajoute à un objet string (StringBuilder). C\'est cet
objet String qui constitue le Flag ! Cependant, il y a une variation à l\'indice 7 du
tableau ! A toi de jouer maintenant !'
    ),
    (5, 'Résout l\'Hydre_de_lerne avant'),
    (
        6,
        'Un certain David et un certain Lloyd ont déjà trouvé une solution à ce problème…'
    ),
    (
        7,
        'Les apostrophes et les commentaires SQL fonctionnent de la même manière dans
SQLite, peu importe le langage de programmation utilisé.'
    ),
    (
        8,
        'Nos experts suggèrent d\'utiliser des outils spécialisés en
stéganographie pour cette tâche.'
    );
INSERT INTO Fichier (id, nom)
VALUES (1, 'ctf-git-lost-commit.zip'),
    (2, 'Hydre_de_lerne.zip'),
    (3, 'HydreDemon.zip'),
    (4, 'Le_Pilote_fantome.zip'),
    (5, 'Le_message_invisible.zip');
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
VALUES (
        1,
        'Le commit perdu',
        'Tu travailles sur un devoir ultra important. Après une longue nuit de code, tu commit
une fonctionnalité essentielle… mais dans un moment de fatigue, tu tapes une
mauvaise commande et ton commit disparaît !
Ton professeur te met -2 sur ta note si tu rends ton devoir en retard. Le soleil
commence à se lever, la date butoir est à 8h00. Tu n\'as pas le temps de tout
recoder.
Peux-tu retrouver le commit perdu et sauver ton année ?',
        'G1THax3er_3wqC8D',
        215,
        'Collaboration',
        2,
        1
    ),
    (
        2,
        'Hydre de Lerne',
        'L\'Hydre de Lerne,
        une créature mythique,
        te barre aujourd\'hui la route. Un corps,
plusieurs têtes, et une apparence monstrueuse. Prouve ton courage en faisant face
à ce défi. N\'ai pas peur de son apparence,
        tu es capable de défaire ce monstre.',
        'its memaaaaaryahoaaaaaa',
        350,
        'Rétroingénierie',
        3,
        2
    ),
    (
        3,
        'HydreDemon',
        'Bon, là, tu n\'es pas capable de défaire ce monstre',
        'imtheflaaaaag',
        450,
        'Rétroingénierie',
        5,
        3
    ),
    (
        4,
        'Le pilote fantôme',
        'Des activités illégales internationales ont été signalées, impliquant le transport de
marchandises prohibées par voie aérienne. Les autorités soupçonnent qu\' un pilote professionnel serait complice de ces opérations,
        utilisant sa position pour traverser les frontières sans éveiller les soupçons.Nous avons quelque indice au sujet de ce pilote: ● Le pilote a travaillé pour au moins 3 compagnies différentes pour brouiller les pistes ● Certains de ses vols ont connu des "incidents" suspects ● Son parcours forme un schéma spécifique à travers plusieurs pays connus pour leurs activités criminelles En tant qu\'analyste de données pour Interpol, votre mission est d\' identifier ce pilote et de retracer son itinéraire complet.Le flag a trouvé est la concaténation de tous les aéroports parcourue par le criminel dans l\'ordre chronologique.
Ex: JFK -> CDG -> MDE -> DXB donne le flag JFKCDGMDEDXB',
        'HNDOSLNRTPRGMEXAMSBOG',
        260,
        'Base de donnée',
        2,
        4
    ),
    (
        5,
        'L\’agence matrimoniale parfaite',
        'Vous travaillez pour une agence matrimoniale révolutionnaire qui garantit des
couples parfaitement compatibles ! Votre mission : créer un algorithme qui assure
des mariages stables entre deux groupes de personnes.
Chaque personne dispose d’une liste de préférences, classant les membres du
groupe opposé par ordre de préférence. Votre objectif est d’associer chaque
individu à un partenaire de manière stable, c\'est-à-dire qu’aucun couple ne doit
vouloir se séparer pour reformer un autre couple plus avantageux pour les deux
parties.
Voici la liste des inscrits à l’agence matrimoniale, avec leurs préférences classées du
plus apprécié au moins apprécié :
Alex : Iris, Fabienne, Léa, Sarah, Julie
Ben : Léa, Julie, Fabienne, Sarah, Iris
Charles : Sarah, Iris, Fabienne, Julie, Léa
David : Julie, Léa, Sarah, Fabienne, Iris
Éric : Fabienne, Sarah, Léa, Iris, Julie
Iris : Ben, David, Charles, Alex, Éric
Fabienne : David, Éric, Ben, Charles, Alex
Léa : Alex, Ben, Charles, Éric, David
Sarah : Charles, Ben, David, Alex, Éric
Julie : Ben, Charles, Alex, Éric, David
Saurez-vous former les couples parfaits ?',
        'ILSJF',
        425,
        'Algorithmie',
        4,
        5
    ),
    (
        6,
        'SQLI Level 1',
        'Tu viens d\'être embauché comme testeur de sécurité par une start-up
technologique qui souhaite évaluer la sécurité de son nouveau portail de connexion
développé en Node.js. Ta mission est de vérifier si le formulaire de connexion est
vulnérable aux injections SQL et de prouver la faille en obtenant l\'accès
administrateur.
Le flag se trouve dans l\'interface administrateur, accessible uniquement après une
connexion réussie en tant qu\'administrateur. Bonne chance.',
        'DEF_IUT{N0D3JS_SQL1_M4ST3R}',
        200,
        'Web',
        2,
        6
    ),
    (
        7,
        'Le message invisible',
        'L\'Agence a intercepté cette image qui semble provenir d\'un groupe suspicieux. Nos
analystes pensent qu\'elle contient un message caché, mais leurs méthodes
d\'analyse habituelles n\'ont rien révélé.
Ta mission est de:
1. Examiner l\'image fournie en utilisant des techniques d\'analyse avancées
2. Trouver et extraire le message caché
3. Nous communiquer le flag qu\'elle contient
Cette mission est prioritaire.',
        'DEF_IUT{ST3G0_1S_FUN}',
        250,
        'Stégonographie',
        2,
        6
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