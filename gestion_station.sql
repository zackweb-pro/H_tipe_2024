-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mer. 05 juin 2024 à 23:50
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_station`
--

-- --------------------------------------------------------

--
-- Structure de la table `places`
--

CREATE TABLE `places` (
  `id` int(11) NOT NULL,
  `id_station` int(11) NOT NULL,
  `id_telesiege` int(11) NOT NULL,
  `state` tinyint(1) DEFAULT 0,
  `unique_code` varchar(255) DEFAULT NULL,
  `reserved_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `places`
--

INSERT INTO `places` (`id`, `id_station`, `id_telesiege`, `state`, `unique_code`, `reserved_by`) VALUES
(100, 3, 19, 0, NULL, NULL),
(101, 3, 19, 0, NULL, NULL),
(102, 3, 19, 0, NULL, NULL),
(103, 3, 19, 0, NULL, NULL),
(104, 3, 20, 0, NULL, NULL),
(105, 3, 20, 0, NULL, NULL),
(106, 3, 20, 0, NULL, NULL),
(107, 3, 20, 0, NULL, NULL),
(108, 4, 21, 1, '6660cc2baf166', 4),
(109, 4, 21, 0, NULL, NULL),
(110, 4, 21, 0, NULL, NULL),
(111, 4, 22, 0, NULL, NULL),
(112, 4, 22, 0, NULL, NULL),
(113, 4, 22, 0, NULL, NULL),
(114, 4, 23, 0, NULL, NULL),
(115, 4, 23, 0, NULL, NULL),
(116, 4, 23, 0, NULL, NULL),
(117, 4, 24, 0, NULL, NULL),
(118, 4, 24, 0, NULL, NULL),
(119, 4, 24, 0, NULL, NULL),
(120, 1, 25, 0, NULL, NULL),
(121, 1, 25, 0, NULL, NULL),
(122, 1, 26, 0, NULL, NULL),
(123, 1, 26, 0, NULL, NULL),
(124, 1, 27, 0, NULL, NULL),
(125, 1, 27, 0, NULL, NULL),
(126, 1, 28, 0, NULL, NULL),
(127, 1, 28, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `stations`
--

CREATE TABLE `stations` (
  `id_station` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `station_name` varchar(255) NOT NULL,
  `nbr_t` int(11) NOT NULL,
  `nbr_p` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `stations`
--

INSERT INTO `stations` (`id_station`, `user_id`, `station_name`, `nbr_t`, `nbr_p`) VALUES
(1, 3, 'casablanca', 4, 2),
(3, 6, 'agadir', 2, 4),
(4, 12, 'colombia', 4, 3);

-- --------------------------------------------------------

--
-- Structure de la table `telesieges`
--

CREATE TABLE `telesieges` (
  `id` int(11) NOT NULL,
  `id_station` int(11) NOT NULL,
  `id_telesiege` int(11) NOT NULL,
  `nbr_places` int(11) NOT NULL,
  `state` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `telesieges`
--

INSERT INTO `telesieges` (`id`, `id_station`, `id_telesiege`, `nbr_places`, `state`) VALUES
(19, 3, 1, 4, 0),
(20, 3, 2, 4, 0),
(21, 4, 1, 3, 0),
(22, 4, 2, 3, 0),
(23, 4, 3, 3, 0),
(24, 4, 4, 3, 0),
(25, 1, 1, 2, 0),
(26, 1, 2, 2, 0),
(27, 1, 3, 2, 0),
(28, 1, 4, 2, 0);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `usr` varchar(50) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `poste` varchar(2) NOT NULL,
  `state` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `prenom`, `nom`, `email`, `usr`, `pwd`, `poste`, `state`) VALUES
(2, 'zakaria', 'oumghar', 'adskjfjal@mglaj.sdlf', 'zack', '$2y$10$x0h8L2l7Ak/M7QyN4BfIiuaUVISGOTkyxyjz3oXH0mySgNqgqiz1.', 's', 0),
(3, 'hiba', 'hiba ', 'hiba@gmail.com', 'hiba', '$2y$10$IbhwVDgGzp0Ru0oCR3jUD.53U4HtS.dg4QhXrheIFy2PqaOmOEhN2', 'c', 1),
(4, 'sf', 'asdf', 'asfds2@skdfj.dkjfsa', 'hhh', '$2y$10$nj4Xdww6ssxAPEfoTM/.uOgB.oLmDPt4WeR7whsxAhIxyiJgvvrCi', 's', 0),
(6, 'Zakaria', 'OUMGHAR', 'zakariaoumghar1@gmail.com', 'zackey', '$2y$10$1RzLf6h00uRMIUyjsXz3A.5jJspZJ9AqPpDiz9z2P.YCd0nQWx5wC', 'c', 1),
(11, 'Zakaria', 'OUMGHAR', 'youness@gmail.com', 'youness', '$2y$10$wU6Ch1uWw6PGvldeMXLT2uy3na5JFOnEi.vIrAlxNAhDjo/Xjz0Du', 's', 0),
(12, 'zack', 'zack', 'zack@zack.zack', 'zak', '$2y$10$PFh68zYkFBibx1Z7DElyFuwW2eGPIYZ5VbpcTATWjThkkN7apqC8i', 'c', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_code` (`unique_code`),
  ADD KEY `id_station` (`id_station`),
  ADD KEY `id_telesiege` (`id_telesiege`);

--
-- Index pour la table `stations`
--
ALTER TABLE `stations`
  ADD PRIMARY KEY (`id_station`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `telesieges`
--
ALTER TABLE `telesieges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_station` (`id_station`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `usr` (`usr`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `places`
--
ALTER TABLE `places`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT pour la table `stations`
--
ALTER TABLE `stations`
  MODIFY `id_station` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `telesieges`
--
ALTER TABLE `telesieges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `places`
--
ALTER TABLE `places`
  ADD CONSTRAINT `places_ibfk_1` FOREIGN KEY (`id_station`) REFERENCES `stations` (`id_station`) ON DELETE CASCADE,
  ADD CONSTRAINT `places_ibfk_2` FOREIGN KEY (`id_telesiege`) REFERENCES `telesieges` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `stations`
--
ALTER TABLE `stations`
  ADD CONSTRAINT `stations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `telesieges`
--
ALTER TABLE `telesieges`
  ADD CONSTRAINT `telesieges_ibfk_1` FOREIGN KEY (`id_station`) REFERENCES `stations` (`id_station`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
