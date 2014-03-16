SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

-- -----------------------------------------------------
-- Table `cidades`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cidades` ;

CREATE  TABLE IF NOT EXISTS `cidades` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(45) NOT NULL COMMENT 'nome da cidade' ,
  `uf` VARCHAR(2) NOT NULL COMMENT 'uf',
  PRIMARY KEY (`id`) ,
  INDEX `i_nome` (`nome` ASC) ,
  INDEX `i_uf` (`uf` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'Tabela que contém todas as cidades do brasil';


-- -----------------------------------------------------
-- Table `perfis`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `perfis` ;
CREATE  TABLE IF NOT EXISTS `perfis` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(45) NOT NULL ,
  `criado` DATETIME NOT NULL ,
  `modificado` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `i_nome` (`nome` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'perfis de usuários';
INSERT INTO perfis (id,nome,criado,modificado) VALUES (1,'ADMINISTRADOR',sysdate(),sysdate());
INSERT INTO perfis (id,nome,criado,modificado) VALUES (2,'GERENTE',sysdate(),sysdate());
INSERT INTO perfis (id,nome,criado,modificado) VALUES (3,'USUARIO',sysdate(),sysdate());
INSERT INTO perfis (id,nome,criado,modificado) VALUES (4,'VISITANTE',sysdate(),sysdate());

-- -----------------------------------------------------
-- Table `regionais`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `regionais` ;
CREATE  TABLE IF NOT EXISTS `regionais` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `i_nome` (`nome` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'regionais bh';
INSERT INTO regionais (id,nome) VALUES (1,'BARREIRO');
INSERT INTO regionais (id,nome) VALUES (2,'CENTRO-SUL');
INSERT INTO regionais (id,nome) VALUES (3,'LESTE');
INSERT INTO regionais (id,nome) VALUES (4,'NORDESTE');
INSERT INTO regionais (id,nome) VALUES (5,'NOROESTE');
INSERT INTO regionais (id,nome) VALUES (6,'NORTE');
INSERT INTO regionais (id,nome) VALUES (7,'OESTE');
INSERT INTO regionais (id,nome) VALUES (8,'PAMPULHA');
INSERT INTO regionais (id,nome) VALUES (9,'VENDA NOVA');

-- -----------------------------------------------------
-- Table `territorios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `territorios` ;
CREATE  TABLE IF NOT EXISTS `territorios` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(4) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `i_nome` (`nome` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'territórios de bh';

-- -----------------------------------------------------
-- Table `bairros`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `bairros` ;
CREATE  TABLE IF NOT EXISTS `bairros` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(45) NOT NULL ,
  `territorio_id` INT NOT NULL DEFAULT 1,
  `regional_id` INT NOT NULL DEFAULT 1 ,
  `cidade_id` INT NOT NULL DEFAULT 2302 ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `i_nome` (`nome` ASC) ,
  INDEX `fk_territorio` (`territorio_id` ASC) ,
  INDEX `fk_regional` (`regional_id` ASC) ,
  INDEX `fk_cidades` (`cidade_id` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'bairros bh';

-- -----------------------------------------------------
-- Table `usuarios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `usuarios` ;
CREATE  TABLE IF NOT EXISTS `usuarios` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(45) NOT NULL ,
  `senha` VARCHAR(45) NOT NULL ,
  `ativo` TINYINT(1) NOT NULL DEFAULT true ,
  `nome` VARCHAR(60) NOT NULL ,
  `celular` VARCHAR(13) NOT NULL DEFAULT '',
  `acessos` INT NOT NULL DEFAULT 0 ,
  `trocar_senha` TINYINT(1) NOT NULL DEFAULT false ,
  `cidade_id` INT NOT NULL DEFAULT 2302 ,
  `ultimo_ip` VARCHAR(19) NOT NULL DEFAULT 1 COMMENT 'último ip' ,
  `ultimo_acesso` DATETIME NOT NULL COMMENT 'último acesso' ,
  `criado` DATETIME NOT NULL ,
  `modificado` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `i_ativo` (`ativo` ASC) ,
  INDEX `i_nome` (`nome` ASC) ,
  INDEX `i_email` (`email` ASC) ,
  INDEX `i_acessos` (`acessos` ASC) ,
  INDEX `i_modificado` (`modificado` ASC) ,
  INDEX `i_criado` (`criado` ASC) ,
  INDEX `fk_usuarios_cidades` (`cidade_id` ASC) )
ENGINE = MyISAM;
INSERT INTO `usuarios` (`id`, `senha`, `ativo`, `nome`, `email`, `cidade_id`, `celular`, `ultimo_ip`,`ultimo_acesso`,`criado`,`modificado`) VALUES ('1', '3e32357bdced7fd14deef10e96715200', 1,'ADMINISTRADOR SILVA SAURO', 'admin@admin.com.br', '2302','3112345678','127.0.0.1',sysdate(),sysdate(),sysdate());

-- -----------------------------------------------------
-- Table `usuarios_perfis`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `usuarios_perfis`;
CREATE  TABLE IF NOT EXISTS `usuarios_perfis` (
  `usuario_id` INT NOT NULL ,
  `perfil_id` INT NOT NULL ,
  PRIMARY KEY (`usuario_id`, `perfil_id`) ,
  INDEX `i_usuarios_perfis_perfil` (`perfil_id` ASC) ,
  INDEX `i_usuarios_perfis_usuario` (`usuario_id` ASC) )
ENGINE = MyISAM;
INSERT INTO usuarios_perfis (usuario_id,perfil_id) VALUES (1,1);
INSERT INTO usuarios_perfis (usuario_id,perfil_id) VALUES (1,2);
INSERT INTO usuarios_perfis (usuario_id,perfil_id) VALUES (1,3);
INSERT INTO usuarios_perfis (usuario_id,perfil_id) VALUES (1,4);

-- -----------------------------------------------------
-- Table `modulos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `modulos` ;
CREATE  TABLE IF NOT EXISTS `modulos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(45) NOT NULL ,
  `ativo` TINYINT(1) NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `i_nome` (`nome` ASC) ,
  INDEX `i_ativo` (`ativo` ASC) )
ENGINE = MyISAM;
INSERT INTO modulos (id,nome,ativo) VALUES (1,'SISTEMA',1);

-- -----------------------------------------------------
-- Table `configuracoes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `configuracoes` ;
CREATE  TABLE IF NOT EXISTS `configuracoes` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `empresa` VARCHAR(60) NOT NULL DEFAULT 'MOURA INFO',
  `email` VARCHAR(60) NOT NULL DEFAULT 'admin@mourainfo.com',
  `endereco` VARCHAR(60) NOT NULL DEFAULT 'RUA',
  `bairro` VARCHAR(40) NOT NULL DEFAULT 'CENTRO',
  `cep` VARCHAR(8) NOT NULL DEFAULT '30000000',
  `cidade_id` INT NOT NULL DEFAULT 2302 ,
  `tel1` VARCHAR(10) NOT NULL,
  `tel2` VARCHAR(10) NOT NULL,
  `celular` VARCHAR(10) NOT NULL,
  `sql_dump` TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_cidades` (`cidade_id` ASC) )
ENGINE = MyISAM;
INSERT INTO configuracoes (id,empresa,email,cep,tel1,tel2,celular,cidade_id,sql_dump) VALUES (1,'MOURA INFORMÁTICA','moura@mourainfo.com.br','30575000','3112345678','3187654321','3388345687',2302,1);

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

