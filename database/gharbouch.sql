-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2026 at 12:25 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gharbouch`
--

-- --------------------------------------------------------

--
-- Table structure for table `bouquets_commandes`
--

CREATE TABLE `bouquets_commandes` (
  `id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `destinataire` varchar(150) DEFAULT NULL,
  `couleur_ruban` varchar(50) DEFAULT NULL,
  `message_carte` text DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `icone` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `nom`, `slug`, `icone`) VALUES
(1, 'Porte-clés', 'keychain', '🧸'),
(2, 'Sacs', 'bag', '👜'),
(3, 'Fleurs', 'flower', '🌸'),
(4, 'Accessoires', 'Accessories', '🎀');

-- --------------------------------------------------------

--
-- Table structure for table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `nom_client` varchar(200) DEFAULT NULL,
  `email_client` varchar(150) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `statut` enum('en_attente','confirmee','expediee','livree') DEFAULT 'en_attente',
  `date_commande` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fleurs_bouquet`
--

CREATE TABLE `fleurs_bouquet` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `emoji` varchar(10) DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fleurs_bouquet`
--

INSERT INTO `fleurs_bouquet` (`id`, `nom`, `slug`, `emoji`, `prix`) VALUES
(1, 'Tulipe', 'tulip', '🌷', 4.00),
(2, 'Rose', 'rose', '🌹', 7.00),
(3, 'Sunflower', 'sunflower', '🌻', 9.00),
(4, 'Lily', 'lily', '⚘', 8.00),
(5, 'daisy', 'daisy', '🌼', 7.00),
(6, 'Cerisier', 'cherry', '🌸', 5.00),
(7, 'Lavande', 'lavender', '💜', 7.00),
(8, 'Orchid', 'orchid', '🪷', 10.00);

-- --------------------------------------------------------

--
-- Table structure for table `lignes_bouquet`
--

CREATE TABLE `lignes_bouquet` (
  `id` int(11) NOT NULL,
  `bouquet_id` int(11) NOT NULL,
  `fleur_id` int(11) NOT NULL,
  `quantite` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lignes_commande`
--

CREATE TABLE `lignes_commande` (
  `id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `produit_id` int(11) DEFAULT NULL,
  `type_ligne` enum('produit','bouquet') DEFAULT 'produit',
  `nom_article` varchar(200) DEFAULT NULL,
  `quantite` int(11) DEFAULT 1,
  `prix_unitaire` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produits`
--

CREATE TABLE `produits` (
  `id` int(11) NOT NULL,
  `nom` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 10,
  `categorie_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `badge` varchar(50) DEFAULT NULL,
  `date_ajout` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `description`, `prix`, `stock`, `categorie_id`, `image`, `badge`, `date_ajout`) VALUES
(1, 'basic tote bag', 'Chic tote bag, perfect for stylish everyday essentials', 35.00, 10, 2, 'images/basic_tote_bag.jpg', 'Bestseller', '2026-04-21 02:27:07'),
(2, 'summer beach bag', 'Breezy summer bag ideal for sunny beach days', 45.00, 18, 2, 'images/summer_beach_bag.jpg', 'New', '2026-04-21 02:27:07'),
(3, 'mini cutie bag', 'Whimsical mini bag with cute aesthetic style', 38.00, 20, 2, 'images/mini_cutie_bag.jpg', NULL, '2026-04-21 02:27:07'),
(4, 'lilies', 'Elegant lily flower design, soft aesthetic decoration', 8.00, 15, 3, 'images/lily.jpg', 'Bestseller', '2026-04-21 02:27:07'),
(5, 'sunflower', 'Bright sunflower design full of summer vibes', 9.00, 15, 3, 'images/sunflower.jpg', NULL, '2026-04-21 02:27:07'),
(6, 'tulipe', 'Soft tulip-inspired floral design', 5.00, 15, 3, 'images/tulip.jpg', 'Bestseller', '2026-04-21 02:27:07'),
(7, 'rose', 'Classic romantic rose floral style', 7.00, 15, 3, 'images/rose.jpg', NULL, '2026-04-21 02:27:07'),
(8, 'lavande', 'Calming lavender aesthetic floral piece', 10.00, 15, 3, 'images/lavande.jpg', NULL, '2026-04-21 02:27:07'),
(9, 'hello kitty hair clip', 'Cute Hello Kitty inspired hair accessory', 18.00, 25, 4, 'images/hello_kittyy.jpg', 'New', '2026-04-21 02:27:07'),
(10, 'heart headband', 'Stylish heart-themed headband for cute looks', 15.00, 25, 4, 'images/heart_headband.jpg', NULL, '2026-04-21 02:27:07'),
(11, 'big flower scrunchie', 'Oversized floral scrunchie for trendy hairstyles', 10.00, 30, 4, 'images/scrunchi.jpg', 'Trendy', '2026-04-21 02:27:07'),
(12, 'star and moon', 'Magical star and moon aesthetic accessory', 15.00, 20, 1, 'images/moon_star.jpg', 'Bestseller', '2026-04-21 02:27:07'),
(13, 'cherry', 'Cute cherry-themed aesthetic item', 8.00, 20, 1, 'images/cherry.jpg', NULL, '2026-04-21 02:27:07'),
(14, 'mini bear', 'Small adorable bear-inspired design', 10.00, 20, 1, 'images/bear.jpg', NULL, '2026-04-21 02:27:07'),
(15, 'snoopy', 'Snoopy-inspired cute character item', 12.00, 20, 1, 'images/snoopy.jpg', NULL, '2026-04-21 02:27:07');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `date_inscription` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bouquets_commandes`
--
ALTER TABLE `bouquets_commandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commande_id` (`commande_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Indexes for table `fleurs_bouquet`
--
ALTER TABLE `fleurs_bouquet`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `lignes_bouquet`
--
ALTER TABLE `lignes_bouquet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bouquet_id` (`bouquet_id`),
  ADD KEY `fleur_id` (`fleur_id`);

--
-- Indexes for table `lignes_commande`
--
ALTER TABLE `lignes_commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commande_id` (`commande_id`),
  ADD KEY `produit_id` (`produit_id`);

--
-- Indexes for table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categorie_id` (`categorie_id`);

--
-- Indexes for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bouquets_commandes`
--
ALTER TABLE `bouquets_commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fleurs_bouquet`
--
ALTER TABLE `fleurs_bouquet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `lignes_bouquet`
--
ALTER TABLE `lignes_bouquet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lignes_commande`
--
ALTER TABLE `lignes_commande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bouquets_commandes`
--
ALTER TABLE `bouquets_commandes`
  ADD CONSTRAINT `bouquets_commandes_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `lignes_bouquet`
--
ALTER TABLE `lignes_bouquet`
  ADD CONSTRAINT `lignes_bouquet_ibfk_1` FOREIGN KEY (`bouquet_id`) REFERENCES `bouquets_commandes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lignes_bouquet_ibfk_2` FOREIGN KEY (`fleur_id`) REFERENCES `fleurs_bouquet` (`id`);

--
-- Constraints for table `lignes_commande`
--
ALTER TABLE `lignes_commande`
  ADD CONSTRAINT `lignes_commande_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lignes_commande_ibfk_2` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
