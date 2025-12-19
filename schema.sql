CREATE DATABASE IF NOT EXISTS blogcms;
USE blogcms; 

CREATE TABLE IF NOT EXISTS users(
user_id INT PRIMARY KEY AUTO_INCREMENT,
username VARCHAR(50) UNIQUE NOT NULL,
email VARCHAR(50) UNIQUE NOT NULL,
password_hash VARCHAR(100) NOT NULL,
role ENUM('admin','author','editor','subscriber') DEFAULT 'subscriber',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP   
);

CREATE TABLE IF NOT EXISTS categories(
category_id  INT  PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(50) NOT NULL UNIQUE,
description TEXT  
);

CREATE TABLE IF NOT EXISTS post(
id_post INT PRIMARY KEY AUTO_INCREMENT,
title VARCHAR(50) NOT NULL,
content TEXT NOT NULL,
image_url VARCHAR(100),
created_at_po TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
updated_at_po TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,    
user_id INT NULL ,
category_id  INT NOT NULL,
status_Post ENUM('published','draft') DEFAULT 'draft',
view_count INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES categories(category_id )
);

CREATE TABLE IF NOT EXISTS comments(
comment_id INT PRIMARY KEY AUTO_INCREMENT,
author_name VARCHAR(50),
email VARCHAR(50),
content TEXT NOT NULL,
created_at_com TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
id_post  INT NOT NULL,
status_com ENUM ('approved', 'pending', 'spam') DEFAULT 'pending',
user_id INT ,
FOREIGN KEY (id_post) REFERENCES post(id_post) ON DELETE CASCADE,
FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Insert Data
INSERT INTO users (user_id, username, email, password_hash, role, created_at) VALUES
(1, 'khadija_abirat', 'abiratkhadija@gmail.com', '$2y$10$Yre5QkVuJhGJY7XXBaOJJewm2i00KhmlZ/ycWT59oRC294WEhJcEq', 'admin', '2024-01-15 10:00:00'),
(2, 'marie_dubois', 'marie.dubois@email.com', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 'editor', '2024-02-10 11:30:00'),
(3, 'pierre_leroy', 'pierre.leroy@gmail.com', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 'author', '2024-02-15 09:15:00'),
(4, 'sophie_martin', 'sophie.martin@protonmail.com', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 'author', '2024-03-01 14:20:00'),
(5, 'jean_dupont', 'jean.dupont@yahoo.fr', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 'author', '2024-03-10 16:45:00'),
(6, 'lucie_bernard', 'lucie.bernard@email.com', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 'author', '2024-03-15 08:00:00'),
(7, 'thomas_petit', 'thomas.petit@gmail.com', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 'subscriber', '2024-03-20 10:30:00'),
(8, 'julie_roux', 'julie.roux@protonmail.com', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 'subscriber', '2024-04-01 12:00:00'),
(9, 'marc_vincent', 'marc.vincent@yahoo.fr', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 'author', '2024-04-05 15:45:00'),
(10, 'isabelle_leroy', 'isabelle.leroy@gmail.com', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 'subscriber', '2024-04-10 18:20:00'),
(11, 'david_morel', 'david.morel@email.com', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 'author', '2024-04-15 09:30:00'),
(12, 'caroline_duval', 'caroline.duval@protonmail.com', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 'editor', '2024-05-02 14:10:00'),
(13, 'nicolas_lambert', 'nicolas.lambert@gmail.com', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 'author', '2024-05-10 11:45:00'),
(14, 'elodie_garnier', 'elodie.garnier@email.com', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 'subscriber', '2024-05-20 16:30:00'),
(15, 'antoine_chevalier', 'antoine.chevalier@yahoo.fr', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 'author', '2024-06-01 08:15:00'),
(16, 'clara_royer', 'clara.royer@protonmail.com', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 'author', '2024-06-10 10:40:00'),
(17, 'quentin_menard', 'quentin.menard@gmail.com', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 'subscriber', '2024-06-15 13:25:00'),
(18, 'amelie_colin', 'amelie.colin@email.com', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 'author', '2024-07-03 17:50:00'),
(19, 'vincent_gauthier', 'vincent.gauthier@protonmail.com', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 'editor', '2024-07-12 12:05:00'),
(20, 'marine_lebrun', 'marine.lebrun@gmail.com', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 'subscriber', '2024-07-20 15:20:00');


INSERT INTO categories (category_id, name, description) VALUES
(1, 'Technologie', 'Actualités, tutoriels et analyses sur les nouvelles technologies'),
(2, 'Santé', 'Conseils santé, bien-être et médecine préventive'),
(3, 'Voyage', 'Récits de voyage, guides pratiques et conseils touristiques'),
(4, 'Cuisine', 'Recettes, techniques culinaires et astuces de cuisine'),
(5, 'Sport', 'Actualités sportives, conseils d entraînement et nutrition'),
(6, 'education', 'Pedagogie, conseils d apprentissage et innovations éducatives'),
(7, 'Finance', 'Gestion budgétaire, investissements et actualités économiques'),
(8, 'Mode', 'Tendances, conseils style et actualités de la mode');

INSERT INTO post (id_post, title, content, image_url, created_at_po, updated_at_po, user_id, category_id, status_Post, view_count) VALUES
(1, 'Les Nouvelles Tensions Technologiques en 2024', 'Explorez les technologies émergentes qui vont transformer notre quotidien cette année...', 'tech1.jpg', '2024-02-20 10:00:00', '2024-02-21 11:00:00', 3, 1, 'published', 1245),
(2, 'Comment Maintenir une Bonne Santé au Travail', 'Des conseils pratiques pour préserver votre santé physique et mentale...', 'sante1.jpg', '2024-02-25 14:30:00', '2024-02-25 14:30:00', 4, 2, 'published', 876),
(3, 'Les 10 Destinations à Visiter Absolument en 2024', 'Découvrez les destinations qui vont marquer l''année 2024...', 'voyage1.jpg', '2024-03-05 09:15:00', '2024-03-06 10:30:00', 5, 3, 'draft', 0),
(4, 'Recette du Gâteau au Chocolat Fondant', 'Une recette simple et rapide pour un gâteau au chocolat irrésistible...', 'cuisine1.jpg', '2024-03-10 16:45:00', '2024-03-11 08:30:00', 6, 4, 'published', 2345),
(5, 'Préparation Marathon : Guide Complet du Débutant', 'Tout ce qu''il faut savoir pour préparer son premier marathon...', 'sport1.jpg', '2024-03-15 08:00:00', '2024-03-16 09:15:00', 9, 5, 'published', 1567),
(6, 'Apprendre à Coder en 6 Mois : Mon Parcours', 'Comment j''ai appris la programmation web en moins de 6 mois...', 'education1.jpg', '2024-03-20 10:30:00', '2024-03-21 11:45:00', 11, 6, 'published', 3210),
(7, 'Investir dans la Cryptomonnaie en 2024', 'Guide pour débutants sur les opportunités et risques...', 'finance1.jpg', '2024-04-01 12:00:00', '2024-04-02 13:30:00', 13, 7, 'published', 1890),
(8, 'Les Tendances Mode Printemps-Été 2024', 'Découvrez les must-have de la saison prochaine...', 'mode1.jpg', '2024-04-05 15:45:00', '2024-04-06 16:20:00', 15, 8, 'published', 943),
(9, 'Les Avantages du Télétravail pour la Productivité', 'Comment le travail à distance peut booster vos performances...', 'tech2.jpg', '2024-04-10 18:20:00', '2024-04-11 10:15:00', 18, 1, 'published', 1123),
(10, 'Les Superaliments pour Renforcer l''Immunité', '10 aliments à intégrer dans votre alimentation pour être en forme...', 'sante2.jpg', '2024-04-15 09:30:00', '2024-04-16 14:20:00', 16, 2, 'published', 765),
(11, 'Voyager en Solo : Mes Conseils Sécurité', 'Guide pratique pour voyager seul en toute sécurité...', 'voyage2.jpg', '2024-04-20 11:45:00', '2024-04-21 09:30:00', 19, 3, 'published', 890),
(12, 'Les Bases de la Pâtisserie Française', 'Apprenez les techniques fondamentales de la pâtisserie...', 'cuisine2.jpg', '2024-05-02 14:10:00', '2024-05-03 16:40:00', 20, 4, 'draft', 0),
(13, 'Yoga pour Débutants : Programme sur 30 Jours', 'Un programme progressif pour découvrir le yoga...', 'sport2.jpg', '2024-05-10 11:45:00', '2024-05-11 12:30:00', 5, 5, 'published', 654),
(14, 'Les Méthodes d''Apprentissage des Langues Efficaces', 'Comparatif des différentes approches pour apprendre une langue...', 'education2.jpg', '2024-05-20 16:30:00', '2024-05-21 17:15:00', 6, 6, 'published', 432),
(15, 'Économiser pour Son Premier Achat Immobilier', 'Stratégies pour constituer son apport personnel...', 'finance2.jpg', '2024-06-01 08:15:00', '2024-06-02 10:20:00', 9, 7, 'published', 987),
(16, 'Le Style Minimaliste : Less is More', 'Comment adopter une garde-robe minimaliste et élégante...', 'mode2.jpg', '2024-06-10 10:40:00', '2024-06-11 11:50:00', 11, 8, 'published', 543),
(17, 'Introduction à l''Intelligence Artificielle', 'Comprendre les bases de l''IA et ses applications...', 'tech3.jpg', '2024-06-15 13:25:00', '2024-06-16 14:30:00', 13, 1, 'published', 1876),
(18, 'La Méditation pour Réduire le Stress', 'Techniques de méditation accessibles à tous...', 'sante3.jpg', '2024-07-03 17:50:00', '2024-07-04 18:45:00', 15, 2, 'draft', 0),
(19, 'Voyager avec un Petit Budget', 'Astuces pour voyager sans se ruiner...', 'voyage3.jpg', '2024-07-12 12:05:00', '2024-07-13 13:10:00', 16, 3, 'published', 765),
(20, 'Les Plats Typiques de la Cuisine Marocaine', 'Découverte des saveurs et traditions culinaires du Maroc...', 'cuisine3.jpg', '2024-07-20 15:20:00', '2024-07-21 16:25:00', 18, 4, 'published', 1098),
(21, 'Préparation Physique pour le Ski', 'Conseils pour se préparer avant la saison de ski...', 'sport3.jpg', '2024-08-01 09:30:00', '2024-08-02 10:35:00', 19, 5, 'draft', 0),
(22, 'Les Outils Numériques pour l''Éducation', 'Les meilleures applications pour apprendre et enseigner...', 'education3.jpg', '2024-08-10 14:45:00', '2024-08-11 15:50:00', 20, 6, 'published', 876),
(23, 'Comprendre les Marchés Boursiers', 'Guide pour débutants en bourse...', 'finance3.jpg', '2024-08-20 11:20:00', '2024-08-21 12:25:00', 3, 7, 'published', 654),
(24, 'Le Style Casual Chic au Bureau', 'Comment être élégant tout en restant confortable...', 'mode3.jpg', '2024-09-01 16:30:00', '2024-09-02 17:35:00', 4, 8, 'published', 432),
(25, 'La Sécurité Informatique pour les Particuliers', 'Protégez vos données et votre vie privée en ligne...', 'tech4.jpg', '2024-09-10 08:15:00', '2024-09-11 09:20:00', 5, 1, 'published', 1567),
(26, 'L''Importance du Sommeil pour la Santé', 'Comment améliorer la qualité de son sommeil...', 'sante4.jpg', '2024-09-20 10:25:00', '2024-09-21 11:30:00', 6, 2, 'published', 765),
(27, 'Les Plus Beaux Villages de France', 'Découverte des villages classés parmi les plus beaux de France...', 'voyage4.jpg', '2024-10-01 14:35:00', '2024-10-02 15:40:00', 9, 3, 'published', 987),
(28, 'Les Techniques de Cuisson Santé', 'Cuire ses aliments tout en préservant les nutriments...', 'cuisine4.jpg', '2024-10-10 16:45:00', '2024-10-11 17:50:00', 11, 4, 'published', 543),
(29, 'Le CrossFit : Avantages et Risques', 'Analyse complète de cette discipline sportive...', 'sport4.jpg', '2024-10-20 12:55:00', '2024-10-21 13:00:00', 13, 5, 'published', 876),
(30, 'Apprendre par le Jeu : La Ludopédagogie', 'Utiliser le jeu comme outil d''apprentissage...', 'education4.jpg', '2024-11-01 09:05:00', '2024-11-02 10:10:00', 15, 6, 'published', 654);

INSERT INTO comments (comment_id, author_name, email, content, created_at_com, id_post, status_com) VALUES
(1, 'Jean Martin', 'jean.martin@email.com', 'Excellent article, très bien documenté !', '2024-02-21 12:30:00', 1, 'approved'),
(2, 'Sophie Leroy', 'sophie.leroy@gmail.com', 'Je ne suis pas tout à fait d''accord sur certains points.', '2024-02-22 15:45:00', 1, 'approved'),
(3, 'Thomas Dubois', 'thomas.dubois@protonmail.com', 'Cela m''a beaucoup aidé, merci pour ces conseils.', '2024-02-25 16:00:00', 2, 'approved'),
(4, 'Marie Petit', 'marie.petit@yahoo.fr', 'Très bel article, les photos sont magnifiques !', '2024-02-26 10:15:00', 3, 'pending'),
(5, 'Pierre Bernard', 'pierre.bernard@gmail.com', 'Je trouve que certains conseils sont dangereux.', '2024-02-27 14:20:00', 4, 'spam'),
(6, 'Julie Vincent', 'julie.vincent@email.com', 'Parfait pour les débutants comme moi, merci !', '2024-02-28 11:30:00', 5, 'approved'),
(7, 'Marc Roux', 'marc.roux@protonmail.com', 'Très détaillé, j''ai appris beaucoup de choses.', '2024-02-29 09:45:00', 6, 'approved'),
(8, 'Isabelle Morel', 'isabelle.morel@yahoo.fr', 'C''est exactement ce que je cherchais, merci !', '2024-03-01 13:00:00', 7, 'approved'),
(9, 'David Colin', 'david.colin@gmail.com', 'Je ne comprends pas la partie sur les investissements.', '2024-03-02 16:15:00', 8, 'pending'),
(10, 'Caroline Lambert', 'caroline.lambert@email.com', 'Super article, j''ai hâte de lire la suite !', '2024-03-03 10:30:00', 9, 'approved'),
(11, 'Nicolas Garnier', 'nicolas.garnier@protonmail.com', 'Les tendances présentées sont déjà dépassées.', '2024-03-04 14:45:00', 10, 'spam'),
(12, 'Élodie Chevalier', 'elodie.chevalier@yahoo.fr', 'Je vais essayer la recette ce week-end !', '2024-03-05 09:00:00', 11, 'approved'),
(13, 'Antoine Royer', 'antoine.royer@gmail.com', 'Bonnes astuces pour économiser, merci.', '2024-03-06 12:15:00', 12, 'approved'),
(14, 'Clara Ménard', 'clara.menard@email.com', 'Le style minimaliste n''est pas pour tout le monde.', '2024-03-07 15:30:00', 13, 'pending'),
(15, 'Quentin Gauthier', 'quentin.gauthier@protonmail.com', 'Très bon tutoriel sur l''IA pour débutants.', '2024-03-08 11:45:00', 14, 'approved'),
(16, 'Amélie Lebrun', 'amelie.lebrun@yahoo.fr', 'La méditation a changé ma vie, merci pour l''article.', '2024-03-09 09:00:00', 15, 'approved'),
(17, 'Vincent Martin', 'vincent.martin@gmail.com', 'Voyager avec un petit budget c''est possible !', '2024-03-10 13:15:00', 16, 'approved'),
(18, 'Marine Dubois', 'marine.dubois@email.com', 'Les plats marocains sont délicieux, merci.', '2024-03-11 16:30:00', 17, 'pending'),
(19, 'Alexandre Leroy', 'alexandre.leroy@protonmail.com', 'Je me prépare pour le ski, merci pour les conseils.', '2024-03-12 10:45:00', 18, 'approved'),
(20, 'Sarah Petit', 'sarah.petit@yahoo.fr', 'Quelles applications recommandez-vous pour les enfants ?', '2024-03-13 14:00:00', 19, 'approved'),
(21, 'Paul Bernard', 'paul.bernard@gmail.com', 'La bourse me fait peur, merci pour les explications.', '2024-03-14 09:15:00', 20, 'approved'),
(22, 'Laura Vincent', 'laura.vincent@email.com', 'Je cherche justement un style casual chic, merci !', '2024-03-15 12:30:00', 21, 'pending'),
(23, 'Mathieu Roux', 'mathieu.roux@protonmail.com', 'Important de parler de sécurité informatique.', '2024-03-16 15:45:00', 22, 'approved'),
(24, 'Emma Morel', 'emma.morel@yahoo.fr', 'Je dors mal, merci pour ces conseils.', '2024-03-17 11:00:00', 23, 'approved'),
(25, 'Hugo Colin', 'hugo.colin@gmail.com', 'J''ai visité certains villages, ils sont magnifiques !', '2024-03-18 09:15:00', 24, 'approved');
