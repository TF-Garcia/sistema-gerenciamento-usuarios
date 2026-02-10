-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 09/02/2026 às 17:37
-- Versão do servidor: 8.2.0
-- Versão do PHP: 8.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `adm_mecanica`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `carros`
--

CREATE TABLE `carros` (
  `carro_id` char(9) NOT NULL,
  `CPF` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `dono` varchar(100) NOT NULL,
  `placa` char(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `modelo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `carros`
--

INSERT INTO `carros` (`carro_id`, `CPF`, `dono`, `placa`, `modelo`) VALUES
('126682257', '1234567804', 'Cliente Exemplo 4', 'ABC0042', 'Modelo B'),
('127297905', '1234567802', 'Cliente Exemplo 2', 'ABC0021', 'Modelo A'),
('127670872', '1234567804', 'Cliente Exemplo 4', 'ABC0041', 'Modelo A'),
('176421838', '1234567803', 'Cliente Exemplo 3', 'ABC0032', 'Modelo B'),
('182689884', '1234567808', 'Cliente Exemplo 8', 'ABC0082', 'Modelo B'),
('242022116', '1234567803', 'Cliente Exemplo 3', 'ABC0031', 'Modelo A'),
('257728200', '1234567805', 'Cliente Exemplo 5', 'ABC0052', 'Modelo B'),
('259338159', '1234567806', 'Cliente Exemplo 6', 'ABC0061', 'Modelo A'),
('276821577', '1234567805', 'Cliente Exemplo 5', 'ABC0051', 'Modelo A'),
('294082765', '1234567808', 'Cliente Exemplo 8', 'ABC0081', 'Modelo A'),
('309499734', '1234567801', 'Cliente Exemplo 1', 'ABC0011', 'Modelo A'),
('337808832', '1234567810', 'Cliente Exemplo 10', 'ABC0102', 'Modelo B'),
('382856514', '1234567804', 'Cliente Exemplo 4', 'ABC0041', 'Modelo A'),
('384575942', '1234567804', 'Cliente Exemplo 4', 'ABC0042', 'Modelo B'),
('385362316', '1234567807', 'Cliente Exemplo 7', 'ABC0072', 'Modelo B'),
('402753596', '1234567810', 'Cliente Exemplo 10', 'ABC0102', 'Modelo B'),
('404906387', '1234567809', 'Cliente Exemplo 9', 'ABC0091', 'Modelo A'),
('430139763', '1234567802', 'Cliente Exemplo 2', 'ABC0021', 'Modelo A'),
('437229868', '1234567805', 'Cliente Exemplo 5', 'ABC0051', 'Modelo A'),
('438072337', '1234567801', 'Cliente Exemplo 1', 'ABC0012', 'Modelo B'),
('539245303', '1234567806', 'Cliente Exemplo 6', 'ABC0062', 'Modelo B'),
('572530791', '1234567806', 'Cliente Exemplo 6', 'ABC0062', 'Modelo B'),
('579503408', '1234567801', 'Cliente Exemplo 1', 'ABC0012', 'Modelo B'),
('597989286', '1234567802', 'Cliente Exemplo 2', 'ABC0022', 'Modelo B'),
('678559594', '1234567807', 'Cliente Exemplo 7', 'ABC0072', 'Modelo B'),
('691841843', '1234567803', 'Cliente Exemplo 3', 'ABC0031', 'Modelo A'),
('716495230', '1234567806', 'Cliente Exemplo 6', 'ABC0061', 'Modelo A'),
('766988572', '1234567807', 'Cliente Exemplo 7', 'ABC0071', 'Modelo A'),
('774416023', '1234567807', 'Cliente Exemplo 7', 'ABC0071', 'Modelo A'),
('791438417', '1234567803', 'Cliente Exemplo 3', 'ABC0032', 'Modelo B'),
('822148547', '1234567809', 'Cliente Exemplo 9', 'ABC0092', 'Modelo B'),
('842087821', '1234567808', 'Cliente Exemplo 8', 'ABC0081', 'Modelo A'),
('845212501', '1234567808', 'Cliente Exemplo 8', 'ABC0082', 'Modelo B'),
('858049891', '1234567809', 'Cliente Exemplo 9', 'ABC0092', 'Modelo B'),
('871822216', '1234567810', 'Cliente Exemplo 10', 'ABC0101', 'Modelo A'),
('889028059', '1234567805', 'Cliente Exemplo 5', 'ABC0052', 'Modelo B'),
('897352312', '1234567809', 'Cliente Exemplo 9', 'ABC0091', 'Modelo A'),
('922912701', '1234567802', 'Cliente Exemplo 2', 'ABC0022', 'Modelo B');

-- --------------------------------------------------------

--
-- Estrutura para tabela `login`
--

CREATE TABLE `login` (
  `usuario` varchar(100) NOT NULL,
  `senha` varchar(128) NOT NULL,
  `email` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `login`
--

INSERT INTO `login` (`usuario`, `senha`, `email`) VALUES
('12345678900', '9e47f539ad524973f6d5b2b1a8da5538e5e2dd81138eccda7b2ab440684209cf890d7cf35c9e60c38fd6039a88cac5df124e7e45da73cfd28f8f745e9380b545', 'tomasfrancisco.carvajal@gmail.com');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pecas`
--

CREATE TABLE `pecas` (
  `peca_id` char(9) NOT NULL,
  `servico_id` char(9) DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `garantia_peca` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `pecas`
--

INSERT INTO `pecas` (`peca_id`, `servico_id`, `nome`, `garantia_peca`) VALUES
('109902691', '491357728', 'Peça 1 carro ABC0102', '2026-02-03'),
('128216310', '558934453', 'Peça 1 carro ABC0081', '2026-02-03'),
('238713590', '581746291', 'Peça 2 carro ABC0052', '2026-02-03'),
('251270236', '507921154', 'Peça 1 carro ABC0021', '2026-02-03'),
('266971887', '301009043', 'Peça 2 carro ABC0012', '2026-02-03'),
('271606968', '402102974', 'Peça 2 carro ABC0012', '2026-02-03'),
('276323057', '581746291', 'Peça 1 carro ABC0052', '2026-02-03'),
('281333894', '146069793', 'Peça 2 carro ABC0082', '2026-02-03'),
('291048065', '799915503', 'Peça 2 carro ABC0031', '2026-02-03'),
('292746307', '994328185', 'Peça 2 carro ABC0032', '2026-02-03'),
('297529437', '703463887', 'Peça 1 carro ABC0052', '2026-02-03'),
('318040674', '987467662', 'Peça 2 carro ABC0062', '2026-02-03'),
('325919770', '842269240', 'Peça 1 carro ABC0102', '2026-02-03'),
('326734697', '402102974', 'Peça 1 carro ABC0012', '2026-02-03'),
('332655482', '582853218', 'Peça 2 carro ABC0082', '2026-02-03'),
('334168036', '560293196', 'Peça 1 carro ABC0041', '2026-02-03'),
('351374551', '703463887', 'Peça 2 carro ABC0052', '2026-02-03'),
('372192588', '177642303', 'Peça 1 carro ABC0062', '2026-02-03'),
('375258380', '924996086', 'Peça 1 carro ABC0021', '2026-02-03'),
('376380083', '730049837', 'Peça 1 carro ABC0072', '2026-02-03'),
('377760017', '508117319', 'Peça 2 carro ABC0092', '2026-02-03'),
('408030762', '588955713', 'Peça 2 carro ABC0022', '2026-02-03'),
('413582009', '320015754', 'Peça 2 carro ABC0031', '2026-02-03'),
('415635790', '558404073', 'Peça 1 carro ABC0051', '2026-02-03'),
('421616995', '987467662', 'Peça 1 carro ABC0062', '2026-02-03'),
('432105032', '667347752', 'Peça 2 carro ABC0042', '2026-02-03'),
('432764119', '408279388', 'Peça 2 carro ABC0042', '2026-02-03'),
('500260296', '827193835', 'Peça 1 carro ABC0022', '2026-02-03'),
('524725879', '112563013', 'Peça 2 carro ABC0071', '2026-02-03'),
('525279389', '301009043', 'Peça 1 carro ABC0012', '2026-02-03'),
('525304828', '958424798', 'Peça 2 carro ABC0101', '2026-02-03'),
('531590762', '668599578', 'Peça 2 carro ABC0041', '2026-02-03'),
('544596492', '799915503', 'Peça 1 carro ABC0031', '2026-02-03'),
('555727783', '588955713', 'Peça 1 carro ABC0022', '2026-02-03'),
('568156538', '797833199', 'Peça 1 carro ABC0011', '2026-02-03'),
('609276552', '663827052', 'Peça 1 carro ABC0091', '2026-02-03'),
('609687389', '971195382', 'Peça 2 carro ABC0051', '2026-02-03'),
('611969049', '663827052', 'Peça 2 carro ABC0091', '2026-02-03'),
('629126621', '665872370', 'Peça 2 carro ABC0081', '2026-02-03'),
('647725112', '730049837', 'Peça 2 carro ABC0072', '2026-02-03'),
('648523511', '408279388', 'Peça 1 carro ABC0042', '2026-02-03'),
('652110600', '507921154', 'Peça 2 carro ABC0021', '2026-02-03'),
('675456643', '177642303', 'Peça 2 carro ABC0062', '2026-02-03'),
('698054349', '667347752', 'Peça 1 carro ABC0042', '2026-02-03'),
('732399701', '991729290', 'Peça 2 carro ABC0072', '2026-02-03'),
('737146194', '508117319', 'Peça 1 carro ABC0092', '2026-02-03'),
('741220933', '224501925', 'Peça 1 carro ABC0061', '2026-02-03'),
('742974393', '582853218', 'Peça 1 carro ABC0082', '2026-02-03'),
('753716259', '761774630', 'Peça 1 carro ABC0071', '2026-02-03'),
('753728640', '761774630', 'Peça 2 carro ABC0071', '2026-02-03'),
('756101549', '752450829', 'Peça 2 carro ABC0092', '2026-02-03'),
('756239072', '842269240', 'Peça 2 carro ABC0102', '2026-02-03'),
('771110886', '224501925', 'Peça 2 carro ABC0061', '2026-02-03'),
('785776116', '112563013', 'Peça 1 carro ABC0071', '2026-02-03'),
('791378374', '332545598', 'Peça 1 carro ABC0061', '2026-02-03'),
('796266253', '491924734', 'Peça 1 carro ABC0032', '2026-02-03'),
('797716883', '515661365', 'Peça 1 carro ABC0091', '2026-02-03'),
('801928183', '146069793', 'Peça 1 carro ABC0082', '2026-02-03'),
('807793608', '665872370', 'Peça 1 carro ABC0081', '2026-02-03'),
('808993996', '560293196', 'Peça 2 carro ABC0041', '2026-02-03'),
('828350336', '515661365', 'Peça 2 carro ABC0091', '2026-02-03'),
('829295806', '558404073', 'Peça 2 carro ABC0051', '2026-02-03'),
('829296646', '797833199', 'Peça 2 carro ABC0011', '2026-02-03'),
('835022383', '491357728', 'Peça 2 carro ABC0102', '2026-02-03'),
('896216234', '491924734', 'Peça 2 carro ABC0032', '2026-02-03'),
('903064726', '752450829', 'Peça 1 carro ABC0092', '2026-02-03'),
('903590039', '924996086', 'Peça 2 carro ABC0021', '2026-02-03'),
('915381668', '991729290', 'Peça 1 carro ABC0072', '2026-02-03'),
('922939450', '994328185', 'Peça 1 carro ABC0032', '2026-02-03'),
('926306913', '827193835', 'Peça 2 carro ABC0022', '2026-02-03'),
('928391634', '668599578', 'Peça 1 carro ABC0041', '2026-02-03'),
('956904774', '320015754', 'Peça 1 carro ABC0031', '2026-02-03'),
('961556249', '558934453', 'Peça 2 carro ABC0081', '2026-02-03'),
('962402922', '971195382', 'Peça 1 carro ABC0051', '2026-02-03'),
('973281024', '958424798', 'Peça 1 carro ABC0101', '2026-02-03'),
('992787059', '332545598', 'Peça 2 carro ABC0061', '2026-02-03');

-- --------------------------------------------------------

--
-- Estrutura para tabela `servicos`
--

CREATE TABLE `servicos` (
  `servico_id` char(9) NOT NULL,
  `carro_id` char(9) DEFAULT NULL,
  `descricao` text,
  `valor` decimal(10,2) DEFAULT NULL,
  `garantia_servico` date DEFAULT NULL,
  `data_servico` date DEFAULT NULL,
  `meses_garantia` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `servicos`
--

INSERT INTO `servicos` (`servico_id`, `carro_id`, `descricao`, `valor`, `garantia_servico`, `data_servico`, `meses_garantia`) VALUES
('112563013', '774416023', 'Serviço padrão carro ABC0071', 1109.00, '2025-11-03', '2025-08-03', '3'),
('146069793', '845212501', 'Serviço padrão carro ABC0082', 594.00, '2026-05-03', '2025-08-03', '9'),
('177642303', '572530791', 'Serviço padrão carro ABC0062', 984.00, '2025-11-03', '2025-08-03', '3'),
('224501925', '716495230', 'Serviço padrão carro ABC0061', 497.00, '2026-05-03', '2025-08-03', '9'),
('301009043', '438072337', 'Serviço padrão carro ABC0012', 628.00, '2026-02-03', '2025-08-03', '6'),
('320015754', '242022116', 'Serviço padrão carro ABC0031', 1156.00, '2026-05-03', '2025-08-03', '9'),
('332545598', '259338159', 'Serviço padrão carro ABC0061', 872.00, '2026-05-03', '2025-08-03', '9'),
('402102974', '579503408', 'Serviço padrão carro ABC0012', 1173.00, '2026-02-03', '2025-08-03', '6'),
('408279388', '126682257', 'Serviço padrão carro ABC0042', 766.00, '2026-02-03', '2025-08-03', '6'),
('491357728', '337808832', 'Serviço padrão carro ABC0102', 366.00, '2026-02-03', '2025-08-03', '6'),
('491924734', '791438417', 'Serviço padrão carro ABC0032', 234.00, '2025-11-03', '2025-08-03', '3'),
('507921154', '127297905', 'Serviço padrão carro ABC0021', 955.00, '2026-02-03', '2025-08-03', '6'),
('508117319', '822148547', 'Serviço padrão carro ABC0092', 872.00, '2025-11-03', '2025-08-03', '3'),
('515661365', '897352312', 'Serviço padrão carro ABC0091', 983.00, '2026-05-03', '2025-08-03', '9'),
('558404073', '276821577', 'Serviço padrão carro ABC0051', 1330.00, '2026-02-03', '2025-08-03', '6'),
('558934453', '842087821', 'Serviço padrão carro ABC0081', 1264.00, '2026-02-03', '2025-08-03', '6'),
('560293196', '127670872', 'Serviço padrão carro ABC0041', 266.00, '2025-11-03', '2025-08-03', '3'),
('581746291', '889028059', 'Serviço padrão carro ABC0052', 436.00, '2026-05-03', '2025-08-03', '9'),
('582853218', '182689884', 'Serviço padrão carro ABC0082', 669.00, '2026-05-03', '2025-08-03', '9'),
('588955713', '922912701', 'Serviço padrão carro ABC0022', 1402.00, '2026-05-03', '2025-08-03', '9'),
('663827052', '404906387', 'Serviço padrão carro ABC0091', 1194.00, '2026-05-03', '2025-08-03', '9'),
('665872370', '294082765', 'Serviço padrão carro ABC0081', 1131.00, '2026-02-03', '2025-08-03', '6'),
('667347752', '384575942', 'Serviço padrão carro ABC0042', 599.00, '2026-02-03', '2025-08-03', '6'),
('668599578', '382856514', 'Serviço padrão carro ABC0041', 512.00, '2025-11-03', '2025-08-03', '3'),
('703463887', '257728200', 'Serviço padrão carro ABC0052', 969.00, '2026-05-03', '2025-08-03', '9'),
('730049837', '385362316', 'Serviço padrão carro ABC0072', 270.00, '2026-02-03', '2025-08-03', '6'),
('752450829', '858049891', 'Serviço padrão carro ABC0092', 1330.00, '2025-11-03', '2025-08-03', '3'),
('761774630', '766988572', 'Serviço padrão carro ABC0071', 1212.00, '2025-11-03', '2025-08-03', '3'),
('797833199', '309499734', 'Serviço padrão carro ABC0011', 977.00, '2025-11-03', '2025-08-03', '3'),
('799915503', '691841843', 'Serviço padrão carro ABC0031', 926.00, '2026-05-03', '2025-08-03', '9'),
('827193835', '597989286', 'Serviço padrão carro ABC0022', 610.00, '2026-05-03', '2025-08-03', '9'),
('842269240', '402753596', 'Serviço padrão carro ABC0102', 629.00, '2026-02-03', '2025-08-03', '6'),
('924996086', '430139763', 'Serviço padrão carro ABC0021', 456.00, '2026-02-03', '2025-08-03', '6'),
('958424798', '871822216', 'Serviço padrão carro ABC0101', 1315.00, '2025-11-03', '2025-08-03', '3'),
('971195382', '437229868', 'Serviço padrão carro ABC0051', 1367.00, '2026-02-03', '2025-08-03', '6'),
('987467662', '539245303', 'Serviço padrão carro ABC0062', 663.00, '2025-11-03', '2025-08-03', '3'),
('991729290', '678559594', 'Serviço padrão carro ABC0072', 339.00, '2026-02-03', '2025-08-03', '6'),
('994328185', '176421838', 'Serviço padrão carro ABC0032', 1253.00, '2025-11-03', '2025-08-03', '3');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `carros`
--
ALTER TABLE `carros`
  ADD PRIMARY KEY (`carro_id`,`CPF`);

--
-- Índices de tabela `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`usuario`);

--
-- Índices de tabela `pecas`
--
ALTER TABLE `pecas`
  ADD PRIMARY KEY (`peca_id`),
  ADD KEY `pecas_ibfk_1` (`servico_id`);

--
-- Índices de tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`servico_id`),
  ADD KEY `servicos_ibfk_1` (`carro_id`);

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `pecas`
--
ALTER TABLE `pecas`
  ADD CONSTRAINT `pecas_ibfk_1` FOREIGN KEY (`servico_id`) REFERENCES `servicos` (`servico_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Restrições para tabelas `servicos`
--
ALTER TABLE `servicos`
  ADD CONSTRAINT `servicos_ibfk_1` FOREIGN KEY (`carro_id`) REFERENCES `carros` (`carro_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
