Version avec table de liaisons (non-CSV)
```sql
-- Tables principales
CREATE TABLE Utilisateur (
    id INTEGER AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    mail VARCHAR(255) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    score_total INTEGER DEFAULT 0,
    creation_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_co DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT pk_utilisateur PRIMARY KEY (id),
    CONSTRAINT un_utilisateur_mail UNIQUE (mail)
);

CREATE TABLE Defi (
    id INTEGER AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    cle VARCHAR(255) NOT NULL,
    points_recompense INTEGER NOT NULL,
    categorie VARCHAR(255) NOT NULL,
    difficulte INTEGER,
    user_id INTEGER NOT NULL,
    
    CONSTRAINT pk_defi PRIMARY KEY (id),
    CONSTRAINT un_defi_cle UNIQUE (cle),
    CONSTRAINT fk_defi_utilisateur 
        FOREIGN KEY (user_id) 
        REFERENCES Utilisateur(id) 
        ON DELETE CASCADE,
    CONSTRAINT chk_difficulte 
        CHECK (difficulte BETWEEN 1 AND 5)
);

-- Tables pour remplacer les CSV
CREATE TABLE Tag (
    id INTEGER AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    
    CONSTRAINT pk_tag PRIMARY KEY (id),
    CONSTRAINT un_tag_nom UNIQUE (nom)
);

CREATE TABLE Indice (
    id INTEGER AUTO_INCREMENT,
    contenu TEXT NOT NULL,
    
    CONSTRAINT pk_indice PRIMARY KEY (id)
);

CREATE TABLE Fichier (
    id INTEGER AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    
    CONSTRAINT pk_fichier PRIMARY KEY (id),
    CONSTRAINT un_fichier_nom UNIQUE (nom)
);

-- Tables de liaison
CREATE TABLE Defi_Tag (
    defi_id INTEGER NOT NULL,
    tag_id INTEGER NOT NULL,
    
    CONSTRAINT pk_defi_tag PRIMARY KEY (defi_id, tag_id),
    CONSTRAINT fk_defi_tag_defi 
        FOREIGN KEY (defi_id) 
        REFERENCES Defi(id) 
        ON DELETE CASCADE,
    CONSTRAINT fk_defi_tag_tag 
        FOREIGN KEY (tag_id) 
        REFERENCES Tag(id) 
        ON DELETE CASCADE
);

CREATE TABLE Defi_Indice (
    defi_id INTEGER NOT NULL,
    indice_id INTEGER NOT NULL,
    ordre INTEGER NOT NULL,
    
    CONSTRAINT pk_defi_indice PRIMARY KEY (defi_id, indice_id),
    CONSTRAINT fk_defi_indice_defi 
        FOREIGN KEY (defi_id) 
        REFERENCES Defi(id) 
        ON DELETE CASCADE,
    CONSTRAINT fk_defi_indice_indice 
        FOREIGN KEY (indice_id) 
        REFERENCES Indice(id) 
        ON DELETE CASCADE,
    CONSTRAINT un_defi_indice_ordre 
        UNIQUE (defi_id, ordre)
);

CREATE TABLE Defi_Fichier (
    defi_id INTEGER NOT NULL,
    fichier_id INTEGER NOT NULL,
    
    CONSTRAINT pk_defi_fichier PRIMARY KEY (defi_id, fichier_id),
    CONSTRAINT fk_defi_fichier_defi 
        FOREIGN KEY (defi_id) 
        REFERENCES Defi(id) 
        ON DELETE CASCADE,
    CONSTRAINT fk_defi_fichier_fichier 
        FOREIGN KEY (fichier_id) 
        REFERENCES Fichier(id) 
        ON DELETE CASCADE
);

-- Table des defis recents
CREATE TABLE Defi_Utilisateur_Recents (
    user_id INTEGER NOT NULL,
    defi_id INTEGER NOT NULL,
    date_acces DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT pk_defi_utilisateur_recents 
        PRIMARY KEY (user_id, defi_id),
    CONSTRAINT fk_recents_utilisateur 
        FOREIGN KEY (user_id) 
        REFERENCES Utilisateur(id) 
        ON DELETE CASCADE,
    CONSTRAINT fk_recents_defi 
        FOREIGN KEY (defi_id) 
        REFERENCES Defi(id) 
        ON DELETE CASCADE,
    INDEX idx_date_acces (date_acces)
);```