-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 12 juin 2024 à 19:37
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `e-airneis`
--

-- --------------------------------------------------------

--
-- Structure de la table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `designers`
--

CREATE TABLE `designers` (
  `designer_id` int(11) NOT NULL,
  `designer_name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `designers`
--

INSERT INTO `designers` (`designer_id`, `designer_name`, `description`) VALUES
(1, 'Charles Eames', 'Designer américain du XXe siècle, connu pour ses meubles modernes et innovants.'),
(2, 'Arne Jacobsen', 'Architecte et designer danois, célèbre pour ses créations fonctionnelles et intemporelles.'),
(3, 'Philippe Starck', 'Designer français prolifique, reconnu pour son style audacieux et ses objets du quotidien réinventés.'),
(4, 'Patricia Urquiola', 'Architecte et designer espagnole, appréciée pour son approche éclectique et ses matériaux innovants.'),
(5, 'Ronan & Erwan Bouroullec', 'Designers français, connus pour leurs créations minimalistes et poétiques.'),
(6, 'Jasper Morrison', 'Designer britannique, adepte du \"super normal\" et des objets simples et fonctionnels.'),
(7, 'Jean Prouvé', 'Architecte et designer français, pionnier de la construction métallique et du mobilier industriel.'),
(8, 'Charlotte Perriand', 'Architecte et designer française, collaboratrice de Le Corbusier et Pierre Jeanneret.'),
(9, 'Verner Panton', 'Designer danois, célèbre pour ses meubles en plastique aux formes organiques et colorées.'),
(10, 'Achille Castiglioni', 'Designer italien, connu pour son approche expérimentale et ludique du design.');

-- --------------------------------------------------------

--
-- Structure de la table `orderdetails`
--

CREATE TABLE `orderdetails` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `productcategories`
--

CREATE TABLE `productcategories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `productcategories`
--

INSERT INTO `productcategories` (`category_id`, `category_name`) VALUES
(1, 'Mobilier de bureau'),
(2, 'Canapés et fauteuils'),
(3, 'Décoration intérieure'),
(4, 'Luminaires'),
(5, 'Rangement'),
(6, 'Cuisine et salle à manger'),
(7, 'Chambre à coucher'),
(8, 'Salle de bain'),
(9, 'Jardin et extérieur'),
(10, 'Enfants');

-- --------------------------------------------------------

--
-- Structure de la table `productphotos`
--

CREATE TABLE `productphotos` (
  `photo_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `productphotos`
--

INSERT INTO `productphotos` (`photo_id`, `product_id`, `photo_url`, `is_primary`) VALUES
(1, 1, 'chaise-dsw.jpg', 1),
(2, 1, 'chaise-dsw1.jpg', 0),
(3, 2, 'fauteuil-egg.jpg', 1),
(4, 3, 'lampe-pipistrello.jpg', 1),
(5, 4, 'canape-tufty-time.jpg', 1),
(6, 5, 'bibliotheque-aim.png', 1),
(7, 6, 'table-basse-em.jpg', 1),
(8, 7, 'lit-lc4.jpg', 1),
(9, 8, 'suspension-flowerpot.jpg', 1),
(10, 9, 'tabouret-mezzadro.jpg', 1),
(11, 10, 'etagere-string-pocket.jpg', 1);

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `designer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `stock_quantity`, `category_id`, `designer_id`) VALUES
(1, 'Chaise DSW', 'Chaise emblématique en plastique et bois, design Charles & Ray Eames.', 399.99, 25, 1, 1),
(2, 'Fauteuil Egg', 'Fauteuil pivotant en forme d\'œuf, design Arne Jacobsen.', 1299.99, 10, 2, 2),
(3, 'Lampe Pipistrello', 'Lampe de table iconique, design Gae Aulenti.', 499.99, 15, 4, 3),
(4, 'Canapé Tufty-Time', 'Canapé modulaire personnalisable, design Patricia Urquiola.', 2499.99, 5, 2, 4),
(5, 'Bibliothèque AIM', 'Système de rangement modulaire en aluminium, design Ronan & Erwan Bouroullec.', 999.99, 8, 5, 5),
(6, 'Table basse EM', 'Table basse en métal et bois, design Jean Prouvé.', 599.99, 12, 6, 7),
(7, 'Lit LC4', 'Chaise longue réglable emblématique, design Le Corbusier, Pierre Jeanneret et Charlotte Perriand.', 1999.99, 3, 7, 8),
(8, 'Suspension Flowerpot', 'Suspension en métal coloré, design Verner Panton.', 249.99, 20, 4, 9),
(9, 'Tabouret Mezzadro', 'Tabouret de bar réglable en métal et bois, design Achille & Pier Giacomo Castiglioni.', 349.99, 18, 6, 10),
(10, 'Etagère String Pocket', 'Système d\'étagères modulaire et personnalisable, design Nisse & Kajsa Strinning.', 149.99, 30, 5, 2);

-- --------------------------------------------------------

--
-- Structure de la table `promotions`
--

CREATE TABLE `promotions` (
  `promotion_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `discount_percentage` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `promotions`
--

INSERT INTO `promotions` (`promotion_id`, `product_id`, `discount_percentage`, `start_date`, `end_date`) VALUES
(1, 3, 15, '2024-06-01', '2024-06-30'),
(2, 5, 10, '2024-05-15', '2024-07-15'),
(3, 8, 20, '2024-06-10', '2024-06-20');

-- --------------------------------------------------------

--
-- Structure de la table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `review_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `registration_date` date DEFAULT NULL,
  `role` tinyint(1) NOT NULL,
  `token` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `first_name`, `last_name`, `address`, `city`, `country`, `phone_number`, `registration_date`, `role`, `token`) VALUES
(1, 'john.doe@example.com', '$2y$13$n2VKWGJaWJU6B0pB6Vdv6eTMX6IxsqXKnDETnbL1.isIrr1ZTaWuq', 'John', 'Doe', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(3, 'tartempiondu94@yopmail.com', '$2y$13$RiSt60cEz8z7sSY9iU9JuOsqfPBdKefU8QwndwBT9Fyv11D9uxLJy', 'Tar', 'Tarik', NULL, NULL, NULL, NULL, NULL, 0, '0'),
(5, 'fabio@gmail.com', '$2y$13$NijDKjUVnXEGkiiVQitRvu37d.cdCStI8FmCWDGX183s71NI7OAfe', 'test', 'test', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(6, 'fabio1@gmail.com', '$2y$13$4EaioiqdoRd5b884Aq1Nhu57NgXs4Uhi9QsG2Mq9FcHynwXVFW8PK', 'feezf', 'ezfzef', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(8, 'john.doe1@example.com', '$2y$13$36u3b7x10cjD0krTKx7wwukO9jNcUDzCnjfKjLtmAnfcfB4Om6VJm', 'John', 'Doe', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(9, 'john.doe12@example.com', '$2y$13$6TPJKU0o75JtcGn6/fkk5.iQl1/sp8/MAmRQZdbp9alExb1drk2I.', 'John', 'Doe', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(10, 'john.doe22@example.com', '$2y$13$AjWM4MtCc29zUNq9moe4MuGB37CuoWtMdx9g2nHh8WBhzYPs2SWu2', 'John', 'Doe', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(11, 'john.doe232@example.com', '$2y$13$HpctEEougFPMiTyk9PfeAe5Z3Ol/c8OBh12dsWmHNWg5Sbmpb1Ima', 'John', 'Doe', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(12, 'afafaf@gmail.com', '$2y$13$QRIAsczocxkdpGZ9oEjym.BCACPzhZTXRAQSgZna4xOtX9tqOfMae', 'feezf', 'fafa', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(13, 'john.doe2321@example.com', '$2y$13$jXkr7u.T8ykmXEIsDidJ3eXKg7LQp0ItPRXH04JUV2HdnnannmofO', 'John', 'Doe', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(14, 'john.doe23211@example.com', '$2y$13$y5OBfz0ZTMwY9wh4N8xO/OMc1d1htjDTZ2ulYWx113Tz.TRGgycvW', 'John', 'Doe', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(15, 'afafaf11@gmail.com', '$2y$13$vEIt9JLkMaWeMb90VIgYW.tQudMdy6Dc.5DsDeVXZVtUS2AgC39Sm', 'feezf', 'fafa', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(16, 'zvezevzevze@gmail.com', '$2y$13$wNm9VoOzdwb.fTx42qGpWuLxN.5vVJm.9wRTR9BmdtS8RkWJtQQeW', 'zevezv', 'vezvzev', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(17, 'zvezevzevze1@gmail.com', '$2y$13$iQmL9hDVJyoc8wIOdafp3uZifWJXWdPdbmqG902SoV0GtymCCVQAm', 'zevezv', 'vezvzev', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(19, 'zvezevzevze1222@gmail.com', '$2y$13$rnHFyio8cZSfMABcVqhETuaLBrUMq9APzn60rmV9pZFFXJYXZ8mxa', 'zevezv', 'vezvzev', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(20, 'zvezevzevze12225@gmail.com', '$2y$13$lXYbgFFiMbYqvWEX6E/zQOxYbgM2ugPjDaLGQHIPAzC5LX0xSwjNW', 'zevezv', 'vezvzev', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(21, 'test891@gmail.com', '$2y$13$Zo7e2YgKFu5YmJ0nLBEc6e.Rg/Bcf6x1cBAoWVbo.C/3CR7cXWFs2', 'fezczec', 'zczecze', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(22, 'acacaca@gmail.com', '$2y$13$hQUpxIrb9QMASY2F37Vg1.LVLZx8Tjjps2eNN/ug5wFBrKj081nHy', 'Tar', 'aca', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(23, 'tartempiondu9411@yopmail.com', '$2y$13$sxklgMtv62mrpVzgW./Ol.P7ujlKN3ER/UvEcPdZXmVcNIsb0PYu.', 'fzfz', 'fzefzef', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(24, 'zevzevzzvzev@gmail.com', '$2y$13$5TvcR71YVkFkydMKKtRB.enBtsqRpVVmcD6jhnFqrKpvkImuYdW.a', 'zvzev', 'zvzevzev', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(25, 'gergergerg@gmail.com', '$2y$13$PX8AzI3WU.MnVpgMgMuBzeskB6IUX9ngO3GLRbxX6BOvOQ2VvbkpC', 'eggerg', 'ergerger', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(26, 'fzefzf@gmail.com', '$2y$13$Q1TT/X5ZRRPcfQ79V4b8R.rz1MboFzVrQot5Bjq.rx3xFYSi/Q7IW', 'zevezv', 'cvfezf', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(27, 'acacazcazc@gmail.com', '$2y$13$3CFBoDCXEpXTLLUuuCnvv.OA2.1Xu1RU.M/CeYMOqtfcyKRrOghgK', 'cqcqc', 'qazcazcazc', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(28, 'zefzfze@gmail.com', '$2y$13$YBr.m0LW1wHQbcJxV7Ktz.yIaKUZqi9icpfXVs5uCMTqvsvMj18my', 'fezfzf', 'zefzef', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(29, 'okk@gmail.com', '$2y$13$pYI91qekzvKxPAJA7dg8z.nhdW.GjeKLFWIs26mHn8/0L23cGf/wO', 'qcccac', 'acaca', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(30, 'fzfzefz@gmail.com', '$2y$13$kDTgfq1xVeM8b/LgQOtZ1eTXO82HNC.0uyVfXOTFKVfXMmtRfEdpq', 'ZEEFZEF', 'ZFZEFZE', NULL, NULL, NULL, NULL, NULL, 1, '0'),
(31, 'dadazda@mail.com', '$2y$13$t7fH78Wg5bK0sSphyKlo8.Zun2aGt2r/.h8aR/zx68rlmpFAIfSPy', 'raad', 'adadad', NULL, NULL, NULL, NULL, NULL, 1, 'dfabc55278c579ac91e08d567b366ca68819118bd715abeff1f191df198a'),
(32, 'a@gmail.com', '$2y$13$BB6qXiJ6vKlNhHGcT7U6sewyGz5AI79i3WrPjOLeMvwpkgcU96Gve', 'tarik', 'okc', NULL, NULL, NULL, NULL, NULL, 1, 'c718f05dd46c420be4a56a025bad0ef98c34aa75cede9f425da8f88e1faa'),
(33, 'b@gmail.com', '$2y$13$A.K.UBGPbOpmAqnRwPNCCeJ374YC214q0vJ7wgp6y6dSjRK.r0gwC', 'gegerg', 'ergerger', NULL, NULL, NULL, NULL, NULL, 1, '88bf23a5424bb9ec4950f5386bd4b6e2d467b7f5a6f1f796fa04b5cc6958');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index pour la table `designers`
--
ALTER TABLE `designers`
  ADD PRIMARY KEY (`designer_id`);

--
-- Index pour la table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Index pour la table `productcategories`
--
ALTER TABLE `productcategories`
  ADD PRIMARY KEY (`category_id`);

--
-- Index pour la table `productphotos`
--
ALTER TABLE `productphotos`
  ADD PRIMARY KEY (`photo_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `designer_id` (`designer_id`);

--
-- Index pour la table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`promotion_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Contraintes pour la table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `orderdetails_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `orderdetails_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Contraintes pour la table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Contraintes pour la table `productphotos`
--
ALTER TABLE `productphotos`
  ADD CONSTRAINT `productphotos_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `productcategories` (`category_id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`designer_id`) REFERENCES `designers` (`designer_id`);

--
-- Contraintes pour la table `promotions`
--
ALTER TABLE `promotions`
  ADD CONSTRAINT `promotions_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Contraintes pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
