CREATE DATABASE IF NOT EXISTS lire_et_partager;
USE lire_et_partager;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100)
);

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(150) UNIQUE,
    mot_de_passe VARCHAR(255),
    role VARCHAR(20) DEFAULT 'client',
    date_inscription DATETIME DEFAULT NOW()
);

CREATE TABLE livres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(200),
    auteur VARCHAR(150),
    description TEXT,
    prix DECIMAL(6,2),
    couverture VARCHAR(255),
    id_categorie INT,
    date_ajout DATETIME DEFAULT NOW()
);

CREATE TABLE avis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    id_livre INT,
    note INT,
    commentaire TEXT,
    statut VARCHAR(20) DEFAULT 'en_attente',
    date_avis DATETIME DEFAULT NOW()
);

CREATE TABLE suggestions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    titre VARCHAR(200),
    auteur VARCHAR(150),
    commentaire TEXT,
    statut VARCHAR(20) DEFAULT 'en_attente',
    date_suggestion DATETIME DEFAULT NOW()
);

INSERT INTO categories (nom) VALUES ('Roman'), ('Science-fiction'), ('Jeunesse'), ('Essai'), ('Policier');

INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES
('Admin', 'Admin', 'admin@admin.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Dupont', 'Marie', 'marie@mail.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client');

INSERT INTO livres (titre, auteur, description, prix, id_categorie) VALUES
('Le Petit Prince', 'Antoine de Saint-Exupéry', 'Un aviateur rencontre un petit prince dans le désert.', 8.50, 1),
('Dune', 'Frank Herbert', 'Une épopée sur la planète désertique Arrakis.', 11.50, 2),
('Harry Potter', 'J.K. Rowling', 'Un jeune sorcier entre à Poudlard.', 10.00, 3),
('Fondation', 'Isaac Asimov', 'La chute d\'un empire galactique.', 9.90, 2),
('L\'Étranger', 'Albert Camus', 'Meursault face à la justice.', 7.50, 4);

INSERT INTO avis (id_utilisateur, id_livre, note, commentaire, statut) VALUES
(2, 1, 5, 'Un chef-d\'oeuvre absolu.', 'valide'),
(2, 2, 4, 'Très bon livre de SF.', 'valide');
