-- phpMyAdmin SQL Dump
-- version 4.9.6
-- https://www.phpmyadmin.net/
--
-- Hôte : xxx
-- Généré le :  ven. 15 août 2025 à 15:08
-- Version du serveur :  10.4.20-MariaDB-1:10.4.20+maria~stretch-log
-- Version de PHP :  7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `pos_crypto`
--
CREATE DATABASE IF NOT EXISTS `pos_crypto` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `pos_crypto`;

-- --------------------------------------------------------

--
-- Structure de la table `info`
--

CREATE TABLE `info` (
  `id` int(11) NOT NULL,
  `price` float DEFAULT NULL,
  `timestamp` varchar(255) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `info`
--

INSERT INTO `info` (`id`, `price`, `timestamp`, `currency`) VALUES
(1, 153.364, '1719154301', NULL),
(113, 0, '1722677288', 'bitcoin'),
(136, 204.142, '1755261858', 'monero');

-- --------------------------------------------------------

--
-- Structure de la table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `tx_hash` text DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `transactions`
--

INSERT INTO `transactions` (`id`, `label`, `time`, `currency`, `status`, `address`, `tx_hash`, `amount`) VALUES
(1, NULL, '0000-00-00 00:00:00', 'monero', 0, '85HsrhDX5zyUxdK9dJeay4R3xhm7CcJeeWGPRgkPKHthXnTotgnX4yt6dmgemg2DjwRhjgTdqS3ap8HEfEdkVMCx5KFf77N', NULL, NULL),
(2, NULL, '0.33360300 1709633565', 'monero', 0, '881WzWVxXNk4Gm64G6PJDg8aJcCdnnbEyT2Yk5gDGi71fbKarasFi3QZeKocmydJnHcMLeW7iGh9eexN9ZKUTAE1Fpkr3La', NULL, NULL),
(3, NULL, '1709634449.6519', 'monero', 0, '8BWtBtbnQTXDpWhW9dY7mLj7aB7vNQp2vcvc5oKkByNa8jSLJajkTSKiGdn2Ff95pGbC4nqzfwW61AThZ9YiHwqKSQ3vPT4', NULL, NULL),
(4, NULL, '1709634613.4903', 'monero', 0, '869y6mAgEHtL9YXBae6BwURWNGUZTJXfqVag3mqKzXgf8998bEbayWKXMzKCWLCVBsJqpn4acz2aqJ21dRAPixSBJaT479D', NULL, NULL),
(5, NULL, '1709634701.8311', 'monero', 0, '839zpQ2efctbi5qGcHRtup419LmtzLL33hZuzH3VKuik7S5yrdgzP4jGutBJqe2nZoHFA2WSwo36DRMWsQ9qa1ioLWnficc', NULL, NULL),
(6, NULL, '1709636634.8672', 'monero', 0, '8ApoUXZJUxNMNS5X9EgNZWeAabxRdTAS6Pbqm22VWLTBQ8DExUw5J1KYPWUc9XbhRsX6ppk3jDXM1K62Dsy7dyav8t5AgCR', NULL, NULL),
(7, NULL, '1709636669.9248', 'monero', 0, '8AzF36XMZPH5X73P8J6sCDCpJ4Pzh3pa6EccqxqpgxH95gDhCYY54RA3B1oFdHCDjeEDaMDbyu2FuW7qxYYawb2V6Skm5QC', NULL, NULL),
(8, NULL, '1709636691.7106', 'monero', 0, '86eaAFctKgNPp8WxX57gTaMXAPSwruGuKQ5Lc5PhR3GiCEWL7PP5bfBLSEEqPhETFwaKzNiqD6c6UjfFcG3e6LwJ6rZfm47', NULL, NULL),
(9, NULL, '1709636781.8252', 'monero', 0, '841SoD4pDKRBhxHYEdE4GCEpreqZk5hko6D6payBQWUN2NV76nbTCorQwYMatQd32dF8TsmiR76mK73udo8JVXvAQoGfmUj', NULL, NULL),
(10, NULL, '1709636834.0259', 'monero', 0, '87W9UKabfmAhjTJUrYkXb91DYZy1r9wm5cPH8dEFEKezZzv4qCyHqEQiEsostr3qGfNxSvq2ABi1CDm1RSZyQKECJCeqNKy', NULL, NULL),
(11, NULL, '1709636841.6375', 'monero', 0, '865F878qhJFcuWeSMPszksJRCdoq2jvZScxfSPYzzcT5d2ufPVajH3YPoUxbPcgBnQQGJJmq4aAjeMbY3ESDMXD9PhtfBhJ', NULL, NULL),
(12, NULL, '1709636921.0375', 'monero', 0, '89QaX8xzctxYRP1R515L4FcrdmX3kjxSN2w8sPxWxBB27EpR9djkRxHdzPnxweJzM4Gp8MReDYkqx7pT9WrGdZXh3P6jFvg', NULL, NULL),
(13, NULL, '1709636981.5009', 'monero', 0, '87pD2uyKVfeaCVTDi8WFG1ZaXGPEHTALxJGdvzmAdJRaakkUpjfHe3d1v4MPkMqZ7aXrhYSVzmnVdRpHfqtnULwFKervT4P', NULL, NULL),
(14, NULL, '1709637100', 'monero', 0, '83XwzBPg4ZQBT5BT1L8hTmTEw2ZdzJN2oJKWqoYJYpM9Qexj4QnHmWee3QuhsRwc655nq8XiXbU6c7yt94EB9oTDV7b4ALr', NULL, NULL),
(15, NULL, '1709637232', 'monero', 0, '83xsy2UcbDP4HVaj5Uqr533t5FJr2GfH9Lj5wttWLWoh9QPEu27g5JNQkKegGcKkeH13T3A94E5UcgHjAtxvALbf36zfpww', NULL, NULL),
(16, NULL, '1709637259', 'monero', 0, '893de2oLEcEhBv7M4e1ucuTFwVGh3Yo6g9TWxKZ6oMg8j6Q2FnHcxNWZzVVjixgCiXhd3rc7SodQZhzJ1vVjovmX914GntR', NULL, NULL),
(17, NULL, '1709637402', 'monero', 0, '89wfoWbnpwc54o6mRJYTeZNvLBiRAPme1Uk5FV8sefFQhakgwz1mvxsMdj8vtfzgYWNK6upMnJieQFrCBes58foPHcu4zVP', NULL, NULL),
(18, NULL, '1709637629', 'monero', 0, '89UN7qVhHfvTYZHZtdtug4AwsRdzSSQZK9zoYs91fa7sXuqdtrhS3qz7T4Vtru8wQBeEzFPpss6VBaZfpXtT67UaBjuYrwA', NULL, NULL),
(19, NULL, '1709709697', 'monero', 0, '8C4LkyFvFQcQQnweBMJo329JdBX7kVoJpeAG2Y13hDR14ovkJ7AEmfYHJN8dxJkfic6aZM1J5v69xFXtUmEN2D2jFe1S7xj', NULL, NULL),
(20, NULL, '1709709732', 'monero', 0, '87TF5c4ABJUaxBKFwRZrGdPkj2bQY59TRSR15a1XDzoxfukRzNfu1v69oNrFe3BKDkL1z1S8wZopT9Zzh6M7WQBpNwB2WV5', NULL, NULL),
(21, NULL, '1709709759', 'monero', 0, '83gif3opyis1WZGCJKyHRvLAvbBtdghSoBttNjuagLE1FvqzqLSFiRfgUQASzRzAeHbLKByvqLi1MXZ2hz9oscJ2GCCdbuZ', NULL, NULL),
(22, NULL, '1709709970', 'monero', 0, '86Ymi8Z8K4U5AJQwqk3icGgG9bs92rPAAYgNFcY9HFSuFGboEXTcKdee5rdJY6mdCuNqfCjeE97n2fv9jm1yjvmr7xMa572', NULL, NULL),
(23, NULL, '1709710034', 'monero', 0, '85waxkBKD1Y1vAMidmhp9C7KgpBJCZp6VPxQH1ePn79AQezaSX4U97wZKhsgXwbmXmfPDWtpBbLKJQ9RuUqgzyEpFiAovyD', NULL, NULL),
(24, NULL, '1709710551', 'monero', 0, '8AdQhdBDwMojnxbNhhk5DGFujdoxEFdWBDhBiBagqmNo8uS4FKKbA4EKh3s219nmh61s3fLXeAuyjKuPrHYftZTgNppGXic', NULL, NULL),
(25, NULL, '1709715677', 'monero', 0, '87vwANsiYZmb2yVFgcj2AZJfoECQKd8TBehnjBEJfhC6Vos7Sb2tpSiV47FXxDz6bSdnoSiwD9KKL2NeSF6htiiKVJCjiRz', NULL, NULL),
(26, NULL, '1709715693', 'monero', 0, '88s5LLPhq8ADL6k8vdLR8pLqVTE2E4k11iE4a8dxRhMLPe18NNaKvyaPzkkXEAMpGZfPE4VHzgL1LHia1CEAR8MdBZ4dF7Z', NULL, NULL),
(27, '1', '1709803346', 'monero', 0, '8AX7xQ6ZSJGGnvhoSNkjfwejXSQbXyehS6adufbDxqkmL1KhAi6qVb5gew861QPbbaWa63pmzjSvee1gzTRkkE1kBQzoPTe', NULL, '78.98'),
(28, '2', '1718340715', 'monero', 1, '82ZPVChb8oWbGa42SRPDRC3wh7qkE8DiZ5fBKE9EHaDLC7DK9QZsLgbAaqYwfX96J2FUC5G8FnJpsRdwQ7eBxK35Hq4MJsM', NULL, '5'),
(29, '3', '1718340758', 'monero', 1, '89xEj9kixoyEFukjakvhzfZfLNYzmGVzCafSFbX6qVXX1gnDoMkHXWx2Dfx7t1irDa2r2M5zzSSxTfTU5z3kKjMzHkCwGHN', NULL, '5'),
(30, '4', '1718340767', 'monero', 0, '85Kei6mDV3ERQvG4jjQA57DsKowPdbbs4cbayiP4uMEub2wSt6ze2WPaFMSrRWqLSPKrfWqpYNamsgEUibTBuVid8idQZ7f', NULL, '5'),
(31, '5', '1719044048', 'monero', 0, '89LgzkDYe16Rk6hxgP5hdS65bLFL3DiqCgrAZ59VsF1FX4ncaM7PnRXTNmebgSFBXGdd4tq8RysfkEXkSByL2tsaUppQ7Pk', NULL, '2'),
(32, '6', '1719044054', 'monero', 0, '84ZUX7KLjwhKN9CkJuLNvsjZM7BZscyuQ3LUS1RfzUf2DtRXH9kAQvCDeWmLXUTDA18YQQ6HzwoZxLm61wTN5EAH2Y8Zwg4', NULL, '2'),
(33, '7', '1719044503', 'monero', 0, '89hPp2jwYQqUxutBthpgPiSYweGyBPk1CFTeySMY2oToTxA4CBo7FikecbzRSnpRFnPC6qwd7viDPEo9PwcnUP2X99vgmwP', NULL, '2'),
(34, '8', '1719044998', 'monero', 0, '87mcrJWp7KWWhk96SRZQD8h8v6uWUCu9KHRUsFVW6NagRMajnA9UbbW51V9hKReVSHSNo8VSF9YnZ5waz7SVdgJHJaciDUE', NULL, '3'),
(35, '9', '1719047279', 'monero', 1, '85ssoocwEV1KKcp2REdSNSD55VDMVzWtaicrrPofVfju8VPjjmnEKMCTuEqFvbz9gmKK5xAjiitbwM8iNodExVWt9cVvNKr', NULL, '2'),
(36, '10', '1719153997', 'monero', 1, '8BFgn3gKAM5Cr6VBdjdrevEX1qFcqq4dM2vFBzCAzjy4GWZJKZ6ReAgVcioKHEV1q695ErY5Arb4PcesYx5cUv8zQHoKqQb', NULL, '1'),
(37, '11', '1720251802', 'bitcoin', 0, '', NULL, '4.05'),
(38, '12', '1720252149', 'monero', 0, '84DyVyJySRY3gRm21VQDJD54ebMgJ5Gpij5W1PNsaABtaeYNQzG67pha2znh2bA7LdgvqHFzRdj3VD3EfXGESX8J6aKXML8', NULL, '4.05'),
(39, '13', '1720252154', 'bitcoin', 0, 'bc1qu5mknf383jmpu9sr842kqwd6h7448nr49wftql', NULL, '4.05'),
(40, '14', '1722278826', 'bitcoin', 0, 'bc1qc8le8yu5373uynp4wrjzaululne8ucz3wk2yj5', NULL, '3'),
(41, '15', '1722353615', 'bitcoin', 0, 'bc1q43msdawlutc43qp3x0zjdhe0mspevz3vw2u5mu', NULL, '1'),
(42, '16', '1722353732', 'monero', 0, '8BQxHTDV9iTX7jgXz4qK3fX4q8rW4Q2CoFgXCLvCGtqfPCRH2Kq5uuFcF3Hzc3su6d7V1hv16vu7U3cgzhGpFLHMDaakpdL', NULL, '1'),
(43, '17', '1722353973', 'bitcoin', 0, 'bc1qvvmeftlpmepgkk5lwx8z8neu2hsqt3frpgxyjm', NULL, '1'),
(44, '18', '1722443556', 'bitcoin', 1, 'bc1qzhvnjarmdksmf5efvry7zmyput2cm0m6vu53dd', NULL, '2'),
(45, '19', '1722514959', 'bitcoin', 1, 'bc1qkawxpap7y2tda5hyr3e2rywewfcpc4rye3zjlx', NULL, '2.01'),
(46, '20', '1722525602', 'bitcoin', 0, 'bc1q66dk0jazu56kgx9fncwzn4f793e8k65dtutq9q', NULL, '1.98'),
(47, '21', '1722666632', 'monero', 0, '88pecBuC7UPczAK3rc2542TrrE5TEJnuqZzfwT3SsyVCdRiADAwMTF3cknmuYWARPFZfAhLzfhgrGaiQB2JPYTzj655VaF5', NULL, '10.46'),
(48, '22', '1722666636', 'monero', 0, '8AEhb1ewMiGbgYJ8vBA5NK969cLzf8gjReiBSuoFs2HPYbzfW8mQoccfU2W8Wj8D9TPAaeam5bkuY4kjRmRJT6hXANDzNt1', NULL, '10.46'),
(49, '23', '1722667118', 'bitcoin', 1, 'bc1qap2e2ed6s2dzc62r249y8caykhhtlgdr9aeawd', NULL, '2'),
(50, '24', '1722674779', 'monero', 1, '83upWSmUnmr1xjEyK5ZmxC1wGiZLgEyCBPUpo3ic7EX4h4KHcjezVTT7FXJy46iiFB9iCibqNuMq7d9WPLWy5FM6Vkkg2B9', NULL, '2'),
(51, '25', '1722715376', 'monero', 0, '85n11SEKRWv4gE6RMPK7eYh2mwwLfgHhsiiKC1TGJwE4WvwhTJ2SNQm1gKj65t1iKD3cGxPJwQoQTeheCuDjWm1wB9VYzHn', NULL, '10.69'),
(52, '26', '1723831551', 'monero', 0, '84mdfJyRXyZgb4y8EuuNynTZPB91jGcd2hgLykpDjgQxK4AXi2u2wZY5pWCaVndjuoVV1RZnytXfN5R7GhcVYpa7FFCTupQ', NULL, '10.85'),
(53, '27', '1724626199', 'monero', 0, '88ypRNJa3dsMrY3VTrXLoxCXaioPnvb1rfYSz61VDiYDSmRCuKsBJsA9UoHoioSNK7gnaXkGtznWjjgzCpN2paQwBbhL7rW', NULL, '10.50'),
(54, '28', '1731658191', 'monero', 0, '82cq6mVF8Ub1MqrFExXgLk7416eLmH1YsRAk71oTrygrZ6cStvvgJCV8MAJWRx3AEsLVx71Pvm6BGNYuhea9vtBgP4M5qmj', NULL, '5'),
(55, '29', '1731658196', 'monero', 0, '85UxqCwGx9W3FH2ZrXmJSX6pW9bs2TcPLQgjz1HHC5M75TscQoA95L5RTTDcLcYHwqJnQUNMJCvcwakrADR6zj1PLtWwjFL', NULL, '5');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `info_virement` text DEFAULT NULL,
  `uniqueid` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `id_base_monero` int(11) DEFAULT NULL,
  `address_base_monero` varchar(255) DEFAULT NULL,
  `sig_customers` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `info`
--
ALTER TABLE `info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Index pour la table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `info`
--
ALTER TABLE `info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT pour la table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
