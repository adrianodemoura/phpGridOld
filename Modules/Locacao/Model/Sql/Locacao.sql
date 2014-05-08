DROP TABLE IF EXISTS `loc_salas` ;
CREATE  TABLE IF NOT EXISTS `loc_salas` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `titulo` VARCHAR(45) NOT NULL ,
  `ativo` TINYINT(1) NOT NULL DEFAULT 1 ,
  `numero` INT NOT NULL DEFAULT 0,
  `criado` DATETIME NOT NULL ,
  `modificado` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `i_titulo` (`titulo` ASC) ,
  INDEX `i_ativo` (`ativo` ASC) ,
  INDEX `i_modificado` (`modificado` ASC) ,
  INDEX `i_criado` (`criado` ASC) )
ENGINE = MyISAM;

DROP TABLE IF EXISTS `loc_agendas` ;
CREATE  TABLE IF NOT EXISTS `loc_agendas` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `motivo` VARCHAR(99) NOT NULL ,
  `data` DATETIME NOT NULL ,
  `criado` DATETIME NOT NULL ,
  `modificado` DATETIME NOT NULL ,
  `sala_id` INT NOT NULL ,
  `usuario_id` INT NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) ,
  INDEX `i_motivo` (`motivo` ASC) ,
  INDEX `i_data` (`data` ASC) ,
  INDEX `i_sala_id` (`sala_id` ASC) ,
  INDEX `i_modificado` (`modificado` ASC) ,
  INDEX `i_criado` (`criado` ASC) ,
  INDEX `i_usuario_id` (`usuario_id` ASC) )
ENGINE = MyISAM;