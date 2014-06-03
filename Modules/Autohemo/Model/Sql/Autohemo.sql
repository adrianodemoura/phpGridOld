SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `hem_locais`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `hem_locais` ;

CREATE  TABLE IF NOT EXISTS `hem_locais` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(60) NOT NULL ,
  `criado` DATETIME NOT NULL ,
  `modificado` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `i_nome` (`nome` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `hem_retiradas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `hem_retiradas` ;

CREATE  TABLE IF NOT EXISTS `hem_retiradas` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `data` DATETIME NOT NULL ,
  `reti_qtd` DECIMAL(12,2) NOT NULL DEFAULT 0.00 ,
  `usuario_id` INT(11) NOT NULL DEFAULT 0 ,
  `local_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `i_reti_qtd` (`data` ASC) ,
  INDEX `i_usuario_id` (`usuario_id` ASC) ,
  INDEX `fk_local_id` (`local_id` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `hem_aplicacoes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `hem_aplicacoes` ;

CREATE  TABLE IF NOT EXISTS `hem_aplicacoes` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `data` DATETIME NOT NULL ,
  `apli_qtd` DECIMAL(12,2) NOT NULL DEFAULT 0.00 ,
  `usuario_id` INT(11) NOT NULL ,
  `local_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_local_id` (`local_id` ASC) ,
  INDEX `i_data` (`data` ASC) ,
  INDEX `i_usuario_id` (`usuario_id` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `hem_retiradas_aplicacoes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `hem_retiradas_aplicacoes` ;

CREATE  TABLE IF NOT EXISTS `hem_retiradas_aplicacoes` (
  `retirada_id` INT NOT NULL ,
  `aplicacao_id` INT NOT NULL ,
  PRIMARY KEY (`retirada_id`, `aplicacao_id`) ,
  INDEX `fk_aplicacao_id` (`aplicacao_id` ASC) ,
  INDEX `fk_retirada_id` (`retirada_id` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `hem_aplicadores`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `hem_aplicadores` ;

CREATE  TABLE IF NOT EXISTS `hem_aplicadores` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(60) NOT NULL ,
  `email` VARCHAR(60) NOT NULL ,
  `tele_resi` VARCHAR(14) NOT NULL ,
  `celular` VARCHAR(14) NOT NULL ,
  `aniversario` VARCHAR(4) NOT NULL ,
  `cidade_id` INT(11) NOT NULL DEFAULT 2302 ,
  PRIMARY KEY (`id`) ,
  INDEX `i_nome` (`nome` ASC) ,
  INDEX `i_email` (`email` ASC) ,
  INDEX `i_celular` (`celular` ASC) ,
  INDEX `i_aniversario` (`aniversario` ASC) ,
  INDEX `i_cidade_id` (`cidade_id` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `hem_locais`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `hem_locais` (`id`, `nome`, `criado`, `modificado`) VALUES (1, 'Braço Direito', '2014-01-01 12:30:30', '2014-01-01 12:30:31');
INSERT INTO `hem_locais` (`id`, `nome`, `criado`, `modificado`) VALUES (2, 'Braço Esquerdo', '2014-01-01 12:30:30', '2014-01-01 12:30:31');
INSERT INTO `hem_locais` (`id`, `nome`, `criado`, `modificado`) VALUES (3, 'Nádega Direita', '2014-01-01 12:30:30', '2014-01-01 12:30:31');
INSERT INTO `hem_locais` (`id`, `nome`, `criado`, `modificado`) VALUES (4, 'Nádega Esquerda', '2014-01-01 12:30:30', '2014-01-01 12:30:31');
INSERT INTO `hem_locais` (`id`, `nome`, `criado`, `modificado`) VALUES (5, 'Coxa Direita', '2014-01-01 12:30:30', '2014-01-01 12:30:31');
INSERT INTO `hem_locais` (`id`, `nome`, `criado`, `modificado`) VALUES (6, 'Coxa Esquerda', '2014-01-01 12:30:30', '2014-01-01 12:30:31');

COMMIT;
