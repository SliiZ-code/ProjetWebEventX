CREATE TABLE Profile (
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	firstname VARCHAR(255),
	lastname VARCHAR(255),
	picture VARCHAR(255),
	description TEXT,
	creationDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updateDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE Role (
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255),
	creationDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updateDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE User (
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	mail VARCHAR(255) NOT NULL UNIQUE,
	password VARCHAR(255) NOT NULL,
	idRole INTEGER,
	idProfile INTEGER,
	creationDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updateDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	isActive BOOLEAN NOT NULL,
	FOREIGN KEY (idRole) REFERENCES Role(id),
	FOREIGN KEY (idProfile) REFERENCES Profile(id)
);

CREATE TABLE Event (
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255) NOT NULL,
	description VARCHAR(255),
	startDate DATETIME,
	endDate DATETIME,
	ownerId INTEGER NOT NULL,
	creationDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updateDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	FOREIGN KEY (ownerId) REFERENCES User(id)
);

CREATE TABLE Registration (
	userId INTEGER NOT NULL,
	eventId INTEGER NOT NULL,
	creationDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updateDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (userId, eventId),
	FOREIGN KEY (userId) REFERENCES User(id) ON DELETE CASCADE,
	FOREIGN KEY (eventId) REFERENCES Event(id) ON DELETE CASCADE
);

CREATE TABLE Wishlist (
	userId INTEGER NOT NULL,
	eventId INTEGER NOT NULL,
	creationDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updateDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (userId, eventId),
	FOREIGN KEY (userId) REFERENCES User(id) ON DELETE CASCADE,
	FOREIGN KEY (eventId) REFERENCES Event(id) ON DELETE CASCADE
);

-- Insertion des données synthétiques

-- Insertion des rôles
INSERT INTO Role (name, creationDate, updateDate) VALUES
('admin', NOW(), NOW()),
('organizer', NOW(), NOW()),
('participant', NOW(), NOW());

-- Insertion des profils
INSERT INTO Profile (firstname, lastname, picture, description, creationDate, updateDate) VALUES
('Jean', 'Dupont', 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150', 'Administrateur système passionné de technologie et organisateur d\'événements tech.', NOW(), NOW()),
('Marie', 'Martin', 'https://images.unsplash.com/photo-1494790108755-2616b612b5bc?w=150', 'Développeuse full-stack avec 5 ans d\'expérience, spécialisée en React et Node.js.', NOW(), NOW()),
('Pierre', 'Durand', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150', 'Expert en intelligence artificielle et machine learning, speaker reconnu.', NOW(), NOW()),
('Sophie', 'Leroy', 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=150', 'Designer UX/UI spécialisée dans les applications mobiles et l\'accessibilité.', NOW(), NOW()),
('Thomas', 'Bernard', 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150', 'Architecte logiciel et DevOps engineer, organisateur de meetups tech.', NOW(), NOW()),
('Emma', 'Petit', 'https://images.unsplash.com/photo-1489424731084-a5d8b219a5bb?w=150', 'Product Manager avec expertise en méthodologies agiles et innovation.', NOW(), NOW()),
('Lucas', 'Moreau', 'https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?w=150', 'Étudiant en informatique passionné de cybersécurité et blockchain.', NOW(), NOW()),
('Chloé', 'Simon', 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=150', 'Data scientist spécialisée dans l\'analyse prédictive et le Big Data.', NOW(), NOW()),
('Alexandre', 'Rousseau', 'https://images.unsplash.com/photo-1566492031773-4f4e44671d66?w=150', 'Développeur mobile iOS/Android avec passion pour les nouvelles technologies.', NOW(), NOW()),
('Léa', 'Garnier', 'https://images.unsplash.com/photo-1494790108755-2616b612b5bc?w=150', 'Consultante en transformation digitale et formatrice en programmation.', NOW(), NOW());

-- Insertion des utilisateurs
INSERT INTO User (mail, password, idRole, idProfile, creationDate, updateDate, isActive) VALUES
('jean.dupont@eventx.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1, NOW(), NOW(), 1),
('marie.martin@eventx.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 2, NOW(), NOW(), 1),
('pierre.durand@eventx.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 3, NOW(), NOW(), 1),
('sophie.leroy@eventx.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 4, NOW(), NOW(), 1),
('thomas.bernard@eventx.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 5, NOW(), NOW(), 1),
('emma.petit@eventx.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 6, NOW(), NOW(), 1),
('lucas.moreau@eventx.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 7, NOW(), NOW(), 1),
('chloe.simon@eventx.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 8, NOW(), NOW(), 1),
('alexandre.rousseau@eventx.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 9, NOW(), NOW(), 1),
('lea.garnier@eventx.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 10, NOW(), NOW(), 1);

-- Insertion des événements
INSERT INTO Event (name, description, startDate, endDate, ownerId, creationDate, updateDate) VALUES
('DevFest Paris 2024', 'Le plus grand événement développeur de l\'année avec des talks inspirants et des ateliers pratiques.', '2024-11-20 09:00:00', '2024-11-20 19:00:00', 2, NOW(), NOW()),
('Workshop React Hooks Avancés', 'Maîtrisez les hooks personnalisés et l\'optimisation des performances dans React.', '2024-10-28 14:00:00', '2024-10-28 17:30:00', 3, NOW(), NOW()),
('Meetup IA & Ethics', 'Discussion sur l\'éthique en intelligence artificielle et les enjeux sociétaux du ML.', '2024-10-15 19:00:00', '2024-10-15 21:30:00', 3, NOW(), NOW()),
('Hackathon Green Tech', 'Développez des solutions tech pour l\'environnement durant ce week-end intensif.', '2024-11-15 18:00:00', '2024-11-17 18:00:00', 5, NOW(), NOW()),
('Conférence Cybersécurité 2024', 'Les dernières tendances en sécurité informatique et protection des données.', '2024-12-05 09:30:00', '2024-12-05 17:00:00', 2, NOW(), NOW()),
('Atelier Design System', 'Créez et maintenez un design system cohérent pour vos applications.', '2024-10-30 10:00:00', '2024-10-30 13:00:00', 10, NOW(), NOW()),
('Meetup Blockchain & Web3', 'Explorez les technologies décentralisées et leurs applications pratiques.', '2024-11-08 19:30:00', '2024-11-08 21:30:00', 5, NOW(), NOW()),
('Formation Data Science', 'Introduction complète au machine learning et à l\'analyse de données.', '2024-12-10 09:00:00', '2024-12-12 17:00:00', 3, NOW(), NOW()),
('Conférence Mobile Development', 'Développement d\'applications mobiles natives et cross-platform.', '2024-11-25 10:00:00', '2024-11-25 16:30:00', 10, NOW(), NOW());

-- Insertion des inscriptions
INSERT INTO Registration (userId, eventId, creationDate, updateDate) VALUES
-- DevFest Paris 2024 (événement populaire)
(4, 1, NOW(), NOW()),
(5, 1, NOW(), NOW()),
(6, 1, NOW(), NOW()),
(7, 1, NOW(), NOW()),
(8, 1, NOW(), NOW()),
(9, 1, NOW(), NOW()),

-- Workshop React Hooks
(4, 2, NOW(), NOW()),
(6, 2, NOW(), NOW()),
(9, 2, NOW(), NOW()),

-- Meetup IA & Ethics
(5, 3, NOW(), NOW()),
(8, 3, NOW(), NOW()),
(7, 3, NOW(), NOW()),

-- Hackathon Green Tech
(4, 4, NOW(), NOW()),
(6, 4, NOW(), NOW()),
(7, 4, NOW(), NOW()),
(9, 4, NOW(), NOW()),

-- Conférence Cybersécurité
(5, 5, NOW(), NOW()),
(7, 5, NOW(), NOW()),
(8, 5, NOW(), NOW()),

-- Atelier Design System
(4, 6, NOW(), NOW()),
(6, 6, NOW(), NOW()),

-- Meetup Blockchain
(7, 7, NOW(), NOW()),
(9, 7, NOW(), NOW()),
(8, 7, NOW(), NOW()),

-- Formation Data Science
(5, 8, NOW(), NOW()),
(6, 8, NOW(), NOW()),
(8, 8, NOW(), NOW()),

-- Conférence Mobile Development
(4, 9, NOW(), NOW()),
(9, 9, NOW(), NOW()),
(7, 9, NOW(), NOW());

-- Insertion des wishlists (événements que les utilisateurs souhaitent suivre)
INSERT INTO Wishlist (userId, eventId, creationDate, updateDate) VALUES
-- Utilisateurs intéressés par DevFest mais pas encore inscrits
(10, 1, NOW(), NOW()),

-- Workshop React - utilisateurs en attente
(5, 2, NOW(), NOW()),
(7, 2, NOW(), NOW()),
(8, 2, NOW(), NOW()),

-- Hackathon - utilisateurs hésitants
(5, 4, NOW(), NOW()),
(8, 4, NOW(), NOW()),

-- Conférence Cybersécurité - liste d'attente
(4, 5, NOW(), NOW()),
(6, 5, NOW(), NOW()),
(9, 5, NOW(), NOW()),
(10, 5, NOW(), NOW()),

-- Design System - intérêt marqué
(5, 6, NOW(), NOW()),
(7, 6, NOW(), NOW()),
(8, 6, NOW(), NOW()),
(9, 6, NOW(), NOW()),

-- Blockchain meetup - veille technologique
(4, 7, NOW(), NOW()),
(5, 7, NOW(), NOW()),
(6, 7, NOW(), NOW()),

-- Formation Data Science - développement professionnel
(4, 8, NOW(), NOW()),
(7, 8, NOW(), NOW()),
(9, 8, NOW(), NOW()),
(10, 8, NOW(), NOW()),

-- Mobile Development - cross-platform interest
(5, 9, NOW(), NOW()),
(6, 9, NOW(), NOW()),
(8, 9, NOW(), NOW()),
(10, 9, NOW(), NOW());

