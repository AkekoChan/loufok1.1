-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 15 nov. 2023 à 10:35
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `loufok`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `mail_admin` varchar(100) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id_admin`, `mail_admin`, `password`) VALUES
(1, 'fred@loufok.com', '9312dc1a088ec8771ed65c7b7d66fa94de23dfdc7ccb3b003bfd716cf104b129'),
(2, 'zoe@loufok.com', '9312dc1a088ec8771ed65c7b7d66fa94de23dfdc7ccb3b003bfd716cf104b129');

-- --------------------------------------------------------

--
-- Structure de la table `cadavre_exquis`
--

CREATE TABLE `cadavre_exquis` (
  `id_cadavre_exquis` int(11) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `nb_contribution` int(11) DEFAULT NULL,
  `nb_like` int(11) DEFAULT 0,
  `id_admin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cadavre_exquis`
--

INSERT INTO `cadavre_exquis` (`id_cadavre_exquis`, `title`, `date_start`, `date_end`, `nb_contribution`, `nb_like`, `id_admin`) VALUES
(1, 'Super Cadavre', '2023-11-09', '2023-11-25', 3, 0, 1);

--
-- Déclencheurs `cadavre_exquis`
--
DELIMITER $$
CREATE TRIGGER `periode_overlap` BEFORE INSERT ON `cadavre_exquis` FOR EACH ROW BEGIN
    DECLARE overlapping_count INT;

    -- Vérifier s'il y a des cadavres exquis avec des périodes qui se chevauchent
    SELECT COUNT(*) INTO overlapping_count
    FROM Cadavre_Exquis
    WHERE NEW.date_start <= date_end AND NEW.date_end >= date_start;

    -- Si le nombre de cadavres exquis avec des périodes qui se chevauchent est supérieur à 0, signaler une erreur
    IF overlapping_count > 0 OR NEW.date_start >= NEW.date_end THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La période du nouveau cadavre exquis se chevauche avec une période existante.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `contribution`
--

CREATE TABLE `contribution` (
  `id_contribution` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `text` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `submission_order` int(11) DEFAULT NULL,
  `id_cadavre_exquis` int(11) DEFAULT NULL,
  `id_admin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déclencheurs `contribution`
--
DELIMITER $$
CREATE TRIGGER `auto_suborder` BEFORE INSERT ON `contribution` FOR EACH ROW BEGIN
    DECLARE contribution_count INT;
    DECLARE max_submission_order INT;

    -- Compter le nombre de contributions pour l'id_cadavre_exquis de la nouvelle contribution
    SELECT COUNT(*) INTO contribution_count
    FROM Contribution
    WHERE id_cadavre_exquis = NEW.id_cadavre_exquis;

    -- Vérifier si le nombre de contributions est inférieur à nb_max_contributions de l'objet cadavre_exquis
    IF contribution_count >= (SELECT nb_contribution FROM Cadavre_Exquis WHERE id_cadavre_exquis = NEW.id_cadavre_exquis) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le nombre maximum de contributions pour ce cadavre exquis est atteint.';
    END IF;

    -- Trouver le plus grand submission_order pour le même id_cadavre_exquis
    SELECT MAX(submission_order) INTO max_submission_order
    FROM Contribution
    WHERE id_cadavre_exquis = NEW.id_cadavre_exquis;

    -- Mettre à jour submission_order dans la nouvelle contribution
    SET NEW.submission_order = COALESCE(max_submission_order, 0) + 1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `randcontribution`
--

CREATE TABLE `randcontribution` (
  `num_contribution` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_cadavre_exquis` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `mail` varchar(100) DEFAULT NULL,
  `sexe` varchar(1) DEFAULT NULL,
  `bdate` date DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_user`, `nom`, `mail`, `sexe`, `bdate`, `password`) VALUES
(1, 'Jose Garcia', 'jose.garcia@gmail.com', 'M', '1988-08-11', '9312dc1a088ec8771ed65c7b7d66fa94de23dfdc7ccb3b003bfd716cf104b129'),
(2, 'Alphonse Maital', 'alph.maital@full.com', 'M', '1995-03-23', '9312dc1a088ec8771ed65c7b7d66fa94de23dfdc7ccb3b003bfd716cf104b129');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `mail_admin` (`mail_admin`);

--
-- Index pour la table `cadavre_exquis`
--
ALTER TABLE `cadavre_exquis`
  ADD PRIMARY KEY (`id_cadavre_exquis`),
  ADD UNIQUE KEY `title` (`title`),
  ADD KEY `FK_CadavreExquis_Admin` (`id_admin`);

--
-- Index pour la table `contribution`
--
ALTER TABLE `contribution`
  ADD PRIMARY KEY (`id_contribution`),
  ADD KEY `contribution_ibfk_2` (`id_cadavre_exquis`),
  ADD KEY `submission_order` (`submission_order`),
  ADD KEY `FK_Contribution_Admin` (`id_admin`),
  ADD KEY `contribution_ibfk_1` (`id_user`);

--
-- Index pour la table `randcontribution`
--
ALTER TABLE `randcontribution`
  ADD UNIQUE KEY `unique_randcontribution` (`id_user`,`id_cadavre_exquis`),
  ADD KEY `randcontribution_ibfk_2` (`id_cadavre_exquis`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `nom` (`nom`),
  ADD UNIQUE KEY `mail` (`mail`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `cadavre_exquis`
--
ALTER TABLE `cadavre_exquis`
  MODIFY `id_cadavre_exquis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `contribution`
--
ALTER TABLE `contribution`
  MODIFY `id_contribution` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `cadavre_exquis`
--
ALTER TABLE `cadavre_exquis`
  ADD CONSTRAINT `FK_CadavreExquis_Admin` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE CASCADE;

--
-- Contraintes pour la table `contribution`
--
ALTER TABLE `contribution`
  ADD CONSTRAINT `FK_Contribution_Admin` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE CASCADE,
  ADD CONSTRAINT `contribution_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `contribution_ibfk_2` FOREIGN KEY (`id_cadavre_exquis`) REFERENCES `cadavre_exquis` (`id_cadavre_exquis`) ON DELETE CASCADE;

--
-- Contraintes pour la table `randcontribution`
--
ALTER TABLE `randcontribution`
  ADD CONSTRAINT `randcontribution_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `randcontribution_ibfk_2` FOREIGN KEY (`id_cadavre_exquis`) REFERENCES `cadavre_exquis` (`id_cadavre_exquis`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
