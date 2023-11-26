-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 24 nov. 2023 à 12:37
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
(1, 'admin@loufok', '89806ffc12e12254d69c670d35dc2156a0c824a856ab6880e1adab481758087c'),
(2, 'bernadette.chaulet@univ-poitiers.fr', '0fcf421e94000bb74b085c415ee86173210ccb365b24fa1fd850afab1a3e93a8'),
(3, 'stephanie.delayre@univ-poitiers.fr', 'b139006892267bef8f936f75499e7d8b6439bc3006f851303a95b0aa2abd5d88');

-- --------------------------------------------------------

--
-- Structure de la table `cadavre_exquis`
--

CREATE TABLE `cadavre_exquis` (
  `id_cadavre_exquis` int(11) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `max_contributions` int(11) DEFAULT NULL,
  `nb_like` int(11) DEFAULT 0,
  `id_admin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déclencheurs `cadavre_exquis`
--
DELIMITER $$
CREATE TRIGGER `periode_overlap` BEFORE INSERT ON `cadavre_exquis` FOR EACH ROW BEGIN
    DECLARE overlapping_count INT;

    -- Vérifier s'il y a des cadavres exquis avec des périodes qui se chevauchent
    SELECT COUNT(*) INTO overlapping_count
    FROM cadavre_exquis
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
    FROM contribution
    WHERE id_cadavre_exquis = NEW.id_cadavre_exquis;

    -- Vérifier si le nombre de contributions est inférieur à nb_max_contributions de l'objet cadavre_exquis
    IF contribution_count >= (SELECT max_contributions FROM cadavre_exquis WHERE id_cadavre_exquis = NEW.id_cadavre_exquis) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le nombre maximum de contributions pour ce cadavre exquis est atteint.';
    END IF;

    -- Trouver le plus grand submission_order pour le même id_cadavre_exquis
    SELECT MAX(submission_order) INTO max_submission_order
    FROM contribution
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
(1, 'Leo BARRE', 'leo.barre@etu.univ-poitiers.fr', NULL, NULL, 'a3810b3480734feaa82241264c694264d6ddfaec8dc77c0682b151dadb09bebe'),
(2, 'Victor BEAU', 'victor.beau@etu.univ-poitiers.fr', NULL, NULL, '354280349efb7fc0cb52172e2ee04fd9006094c6b300701141e3d428a806311d'),
(3, 'Mathéo BEGUIER', 'matheo.beguier@etu.univ-poitiers.fr', NULL, NULL, 'd886bf96c443c0c09c3cdfdf3ae75c2cf85caf4b738a39ddbca42719a12496e0'),
(4, 'Arthur BURGAUD', 'arthur.burgaud@etu.univ-poitiers.fr', NULL, NULL, '76aa1cda2132f56ddd37c9ac94bb2acfd13fd8c7c1e1c282d0404dd5ccf9d8ba'),
(5, 'Melvyn CHAHAURY', 'melvyn.chahaury@etu.univ-poitiers.fr', NULL, NULL, '10a7a9a1968f5dfe6196ed3fa22edfc785658aa7b9fd79562ff107adbdb4b82a'),
(6, 'Remi CHAMPEAU', 'remi.champeau@etu.univ-poitiers.fr', NULL, NULL, '4c1e20ecbb3271bbeddf47573169ea3283d6040f728bb71e3dfade153aa4b678'),
(7, 'Rayan CHANNA', 'rayan.channa@etu.univ-poitiers.fr', NULL, NULL, '7d283d9efcbd773438544d9584956317e2fa542884c68ded0a22fc208d2bf710'),
(8, 'Antonin COURVOISIER', 'antonin.courvoisier@etu.univ-poitiers.fr', NULL, NULL, '7f05f825bd1df3df76f71fc0eb4d48db8a014b59d014c408a9ca00045740616e'),
(9, 'Braiane DA SILVA CORREIA', 'braiane.da.silva.correia@etu.univ-poitiers.fr', NULL, NULL, '2c098327536efba0c5fff59a1ab5bda1564b48c7022f7c914248018182245b0c'),
(10, 'Ana DA SILVA SANTOS', 'ana.da.silva.santos@etu.univ-poitiers.fr', NULL, NULL, '428c1dadab5719de73c9255238fd48bad448de7ca916067b6e817acb9a1fb140'),
(11, 'Mathieu DALMAS', 'mathieu.dalmas@etu.univ-poitiers.fr', NULL, NULL, '16e4ef2e6a00cb42dfa8a510bca8d1a5b331e5f58f483efe060d389bc54bb965'),
(12, 'Thomas DESBROSSE', 'thomas.desbrosse@etu.univ-poitiers.fr', NULL, NULL, '8db21b9df978ef394a2d438aaf4d84cb2d2b5173649d62997105907e9fc1bd2a'),
(13, 'Tristan DUPORT', 'tristan.duport@etu.univ-poitiers.fr', NULL, NULL, '1db093ba2d75b0b2dd4bbda3bde61c53a31c491d90052f721bb849bd7c10e753'),
(14, 'Killian GOMEZ', 'killian.gomez@etu.univ-poitiers.fr', NULL, NULL, '019b6b383d7381346210b22796ca345bc9d154af6489071bd967b836b3e6fafa'),
(15, 'Leonie GOURICHON', 'leonie.gourichon@etu.univ-poitiers.fr', NULL, NULL, '2675ba263f83d74cc19d07ddecdd7b78e57caabeff0cb6e8910960711f3d64b6'),
(16, 'Claire MATHIEU', 'claire.mathieu@etu.univ-poitiers.fr', NULL, NULL, '07520e92ea8cd60304aa394f9bc6cb10638318d2e6631c91d48382de5d873595'),
(17, 'Clementine MORISSEAU', 'clementine.morisseau@etu.univ-poitiers.fr', NULL, NULL, 'a8d6feb19aab547cd8b8958e3d42e3d04b397a9aa8c70d5a9dd809b928d6142b'),
(18, 'Théo MARTIN', 'tmartin@gmail.com', 'M', NULL, '89806ffc12e12254d69c670d35dc2156a0c824a856ab6880e1adab481758087c'),
(19, 'Maxence GUIVIER', 'maxence.gvr@gmail.com', NULL, NULL, '89806ffc12e12254d69c670d35dc2156a0c824a856ab6880e1adab481758087c');

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
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `cadavre_exquis`
--
ALTER TABLE `cadavre_exquis`
  MODIFY `id_cadavre_exquis` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `contribution`
--
ALTER TABLE `contribution`
  MODIFY `id_contribution` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
