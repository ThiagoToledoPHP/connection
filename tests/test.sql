-- DBTools Manager Professional (Enterprise Edition)
-- Database Dump for: test
-- Backup Generated in: 20/07/2016 12:02:48
-- Database Server Version: MySQL 5.6.20

-- USEGO

SET FOREIGN_KEY_CHECKS=0;
-- GO


--
-- Dumping Tables
--

--
-- Table: client
--
CREATE TABLE `client` 
(
	`id` integer (11) NOT NULL AUTO_INCREMENT , 
	`nome` varchar (255),
	PRIMARY KEY (`id`)
) TYPE=InnoDB CHARACTER SET latin1 COLLATE latin1_swedish_ci;
-- GO

--
-- Dumping Table Data: client
--
BEGIN;
-- GO
INSERT INTO `client` (`id`, `nome`) VALUES(1, 'thiago');
-- GO
COMMIT;
-- GO

--
-- Dumping Tables Foreign Keys
--

--
-- Dumping Triggers
--
SET FOREIGN_KEY_CHECKS=1;
-- GO

