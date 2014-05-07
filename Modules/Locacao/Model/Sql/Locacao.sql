DROP TABLE IF EXISTS `loc_salas` ;
CREATE  TABLE IF NOT EXISTS `loc_salas` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `titulo` VARCHAR(45) NOT NULL ,
  `ativo` TINYINT(1) NOT NULL DEFAULT 1 ,
  `numero` INT NOT NULL ,
  `data` DATETIME NOT NULL ,
  `criado` DATETIME NOT NULL ,
  `modificado` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `i_titulo` (`titulo` ASC) ,
  INDEX `i_ativo` (`ativo` ASC) ,
  INDEX `i_data` (`data` ASC) ,
  INDEX `i_modificado` (`modificado` ASC) ,
  INDEX `i_criado` (`criado` ASC) )
ENGINE = MyISAM;