SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- -----------------------------------------------------
-- Table `cadastros`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `sis_cadastros` ;
CREATE  TABLE IF NOT EXISTS `sis_cadastros` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(45) NOT NULL ,
  `titulo` VARCHAR(45) NOT NULL ,
  `ativo` TINYINT(1) NOT NULL DEFAULT 1 ,
  `modulo_id` INT NOT NULL ,
  `criado` DATETIME NOT NULL ,
  `modificado` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `i_nome` (`nome` ASC) ,
  INDEX `i_titulo` (`titulo` ASC) ,
  INDEX `i_ativo` (`ativo` ASC) ,
  INDEX `i_modificado` (`modificado` ASC) ,
  INDEX `i_criado` (`criado` ASC) ,
  INDEX `fk_modulos` (`modulo_id` ASC) )
ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO sis_cadastros (id,nome,titulo,ativo,modulo_id,criado,modificado) VALUES (1,'BAIRROS','Bairros',1,1,sysdate(),sysdate());
INSERT INTO sis_cadastros (id,nome,titulo,ativo,modulo_id,criado,modificado) VALUES (2,'CADASTROS','Cadastros',1,1,sysdate(),sysdate());
INSERT INTO sis_cadastros (id,nome,titulo,ativo,modulo_id,criado,modificado) VALUES (3,'CIDADES','Cidades',1,1,sysdate(),sysdate());
INSERT INTO sis_cadastros (id,nome,titulo,ativo,modulo_id,criado,modificado) VALUES (4,'CONFIGURACOES','Configurações',1,1,sysdate(),sysdate());
INSERT INTO sis_cadastros (id,nome,titulo,ativo,modulo_id,criado,modificado) VALUES (5,'MODULOS','Módulos',1,1,sysdate(),sysdate());
INSERT INTO sis_cadastros (id,nome,titulo,ativo,modulo_id,criado,modificado) VALUES (6,'PERFIS','Perfis',1,1,sysdate(),sysdate());
INSERT INTO sis_cadastros (id,nome,titulo,ativo,modulo_id,criado,modificado) VALUES (7,'USUARIOS','Usuários',1,1,sysdate(),sysdate());

-- -----------------------------------------------------
-- Table `permissoes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `sis_permissoes` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `modulo_id` INT NOT NULL DEFAULT 1 ,
  `cadastro_id` INT NOT NULL DEFAULT 1 ,
  `perfil_id` INT NOT NULL DEFAULT 1 ,
  `visualizar` TINYINT(1) NOT NULL DEFAULT 0 ,
  `incluir` TINYINT(1) NOT NULL DEFAULT 0 ,
  `alterar` TINYINT(1) NOT NULL DEFAULT 0 ,
  `excluir` TINYINT(1) NOT NULL DEFAULT 0 ,
  `imprimir` TINYINT(1) NOT NULL DEFAULT 0 ,
  `pesquisar` TINYINT(1) NOT NULL DEFAULT 0 ,
  `exportar` TINYINT(1) NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_modulo` (`modulo_id` ASC) ,
  INDEX `fk_cadastro` (`cadastro_id` ASC) ,
  INDEX `fk_perfil` (`perfil_id` ASC) )
ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT = 'Tabela que contém as permissões de cada perfil';
INSERT INTO sis_permissoes (id,modulo_id,cadastro_id,visualizar,incluir,alterar,excluir,imprimir,pesquisar,exportar,perfil_id) VALUES (1,1,7,1,0,1,0,1,1,1,2);
INSERT INTO sis_permissoes (id,modulo_id,cadastro_id,visualizar,incluir,alterar,excluir,imprimir,pesquisar,exportar,perfil_id) VALUES (2,1,7,1,0,1,0,1,1,1,3);
INSERT INTO sis_permissoes (id,modulo_id,cadastro_id,visualizar,incluir,alterar,excluir,imprimir,pesquisar,exportar,perfil_id) VALUES (3,1,7,1,0,1,0,1,1,0,4);

INSERT INTO sis_permissoes (id,modulo_id,cadastro_id,visualizar,incluir,alterar,excluir,imprimir,pesquisar,exportar,perfil_id) VALUES (4,1,3,1,0,0,0,1,1,1,2);
INSERT INTO sis_permissoes (id,modulo_id,cadastro_id,visualizar,incluir,alterar,excluir,imprimir,pesquisar,exportar,perfil_id) VALUES (5,1,3,1,0,0,0,1,1,1,3);
INSERT INTO sis_permissoes (id,modulo_id,cadastro_id,visualizar,incluir,alterar,excluir,imprimir,pesquisar,exportar,perfil_id) VALUES (6,1,3,1,0,0,0,1,1,0,4);

INSERT INTO sis_permissoes (id,modulo_id,cadastro_id,visualizar,incluir,alterar,excluir,imprimir,pesquisar,exportar,perfil_id) VALUES (7,1,1,1,0,0,0,1,1,1,2);
INSERT INTO sis_permissoes (id,modulo_id,cadastro_id,visualizar,incluir,alterar,excluir,imprimir,pesquisar,exportar,perfil_id) VALUES (8,1,1,1,0,0,0,1,1,1,3);
INSERT INTO sis_permissoes (id,modulo_id,cadastro_id,visualizar,incluir,alterar,excluir,imprimir,pesquisar,exportar,perfil_id) VALUES (9,1,1,1,0,0,0,1,1,0,4);

-- -----------------------------------------------------
-- Table `cidades`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `sis_cidades` ;

CREATE  TABLE IF NOT EXISTS `sis_cidades` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(45) NOT NULL COMMENT 'nome da cidade' ,
  `uf` VARCHAR(2) NOT NULL COMMENT 'uf',
  PRIMARY KEY (`id`) ,
  INDEX `i_nome` (`nome` ASC) ,
  INDEX `i_uf` (`uf` ASC) )
ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT = 'Tabela que contém todas as cidades do brasil';


-- -----------------------------------------------------
-- Table `perfis`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `sis_perfis` ;
CREATE  TABLE IF NOT EXISTS `sis_perfis` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(45) NOT NULL ,
  `criado` DATETIME NOT NULL ,
  `modificado` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `i_nome` (`nome` ASC) )
ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT = 'perfis de usuários';
INSERT INTO sis_perfis (id,nome,criado,modificado) VALUES (1,'ADMINISTRADOR',sysdate(),sysdate());
INSERT INTO sis_perfis (id,nome,criado,modificado) VALUES (2,'GERENTE',sysdate(),sysdate());
INSERT INTO sis_perfis (id,nome,criado,modificado) VALUES (3,'USUARIO',sysdate(),sysdate());
INSERT INTO sis_perfis (id,nome,criado,modificado) VALUES (4,'VISITANTE',sysdate(),sysdate());

-- -----------------------------------------------------
-- Table `regionais`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `sis_regionais` ;
CREATE  TABLE IF NOT EXISTS `sis_regionais` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `i_nome` (`nome` ASC) )
ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT = 'regionais bh';
INSERT INTO sis_regionais (id,nome) VALUES (1,'BARREIRO');
INSERT INTO sis_regionais (id,nome) VALUES (2,'CENTRO-SUL');
INSERT INTO sis_regionais (id,nome) VALUES (3,'LESTE');
INSERT INTO sis_regionais (id,nome) VALUES (4,'NORDESTE');
INSERT INTO sis_regionais (id,nome) VALUES (5,'NOROESTE');
INSERT INTO sis_regionais (id,nome) VALUES (6,'NORTE');
INSERT INTO sis_regionais (id,nome) VALUES (7,'OESTE');
INSERT INTO sis_regionais (id,nome) VALUES (8,'PAMPULHA');
INSERT INTO sis_regionais (id,nome) VALUES (9,'VENDA NOVA');

-- -----------------------------------------------------
-- Table `territorios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `sis_territorios` ;
CREATE  TABLE IF NOT EXISTS `sis_territorios` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(4) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `i_nome` (`nome` ASC) )
ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT = 'territórios de bh';

-- -----------------------------------------------------
-- Table `bairros`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `sis_bairros` ;
CREATE  TABLE IF NOT EXISTS `sis_bairros` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(45) NOT NULL ,
  `territorio_id` INT NOT NULL DEFAULT 1,
  `regional_id` INT NOT NULL DEFAULT 1 ,
  `cidade_id` INT NOT NULL DEFAULT 2302 ,
  `criado` DATETIME NOT NULL DEFAULT '2014-03-27 22:10:50',
  `modificado` DATETIME NOT NULL DEFAULT '2014-03-27 22:10:51',
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `i_nome` (`nome` ASC) ,
  INDEX `i_modificado` (`modificado` ASC) ,
  INDEX `fk_territorio` (`territorio_id` ASC) ,
  INDEX `fk_regional` (`regional_id` ASC) ,
  INDEX `fk_cidades` (`cidade_id` ASC) )
ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT = 'bairros bh';

-- -----------------------------------------------------
-- Table `usuarios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `sis_usuarios` ;
CREATE  TABLE IF NOT EXISTS `sis_usuarios` (
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
  INDEX `i_trocarsenha` (`trocar_senha` ASC) ,
  INDEX `i_modificado` (`modificado` ASC) ,
  INDEX `i_criado` (`criado` ASC) ,
  INDEX `fk_usuarios_cidades` (`cidade_id` ASC) )
ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `sis_usuarios` (`id`, `senha`, `ativo`, `nome`, `email`, `cidade_id`, `celular`, `ultimo_ip`,`ultimo_acesso`,`criado`,`modificado`) VALUES ('1', 'e527fc321d4e4166deee7c8a318a586b', 1,'ADMINISTRADOR PHPGRID', 'admin@phpgrid.com', '2302','3112345678','127.0.0.1',sysdate(),sysdate(),sysdate());
INSERT INTO `sis_usuarios` (`id`, `senha`, `ativo`, `nome`, `email`, `cidade_id`, `celular`, `ultimo_ip`,`ultimo_acesso`,`criado`,`modificado`) VALUES ('2', 'e527fc321d4e4166deee7c8a318a586b', 1,'GERENTE PHPGRID', 'gerente@phpgrid.com', '2301','31323245467','127.0.0.1',sysdate(),sysdate(),sysdate());
INSERT INTO `sis_usuarios` (`id`, `senha`, `ativo`, `nome`, `email`, `cidade_id`, `celular`, `ultimo_ip`,`ultimo_acesso`,`criado`,`modificado`) VALUES ('3', 'e527fc321d4e4166deee7c8a318a586b', 1,'USUÁRIO PHPGRID', 'usuario@phpgrid.com', '2303','31111122222','127.0.0.1',sysdate(),sysdate(),sysdate());
INSERT INTO `sis_usuarios` (`id`, `senha`, `ativo`, `nome`, `email`, `cidade_id`, `celular`, `ultimo_ip`,`ultimo_acesso`,`criado`,`modificado`) VALUES ('4', 'e527fc321d4e4166deee7c8a318a586b', 1,'VISITANTE PHPGRID', 'visitante@phpgrid.com', '2303','31111122222','127.0.0.1',sysdate(),sysdate(),sysdate());

-- -----------------------------------------------------
-- Table `usuarios_perfis`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `sis_usuarios_perfis`;
CREATE  TABLE IF NOT EXISTS `sis_usuarios_perfis` (
  `usuario_id` INT NOT NULL ,
  `perfil_id` INT NOT NULL ,
  PRIMARY KEY (`usuario_id`, `perfil_id`) ,
  INDEX `i_usuarios_perfis_perfil` (`perfil_id` ASC) ,
  INDEX `i_usuarios_perfis_usuario` (`usuario_id` ASC) )
ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO sis_usuarios_perfis (usuario_id,perfil_id) VALUES (1,1);
INSERT INTO sis_usuarios_perfis (usuario_id,perfil_id) VALUES (1,2);
INSERT INTO sis_usuarios_perfis (usuario_id,perfil_id) VALUES (1,3);
INSERT INTO sis_usuarios_perfis (usuario_id,perfil_id) VALUES (1,4);

INSERT INTO sis_usuarios_perfis (usuario_id,perfil_id) VALUES (2,2);
INSERT INTO sis_usuarios_perfis (usuario_id,perfil_id) VALUES (2,3);
INSERT INTO sis_usuarios_perfis (usuario_id,perfil_id) VALUES (2,4);

INSERT INTO sis_usuarios_perfis (usuario_id,perfil_id) VALUES (3,3);
INSERT INTO sis_usuarios_perfis (usuario_id,perfil_id) VALUES (3,4);

INSERT INTO sis_usuarios_perfis (usuario_id,perfil_id) VALUES (4,4);

-- -----------------------------------------------------
-- Table `modulos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `sis_modulos` ;
CREATE  TABLE IF NOT EXISTS `sis_modulos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(45) NOT NULL ,
  `titulo` VARCHAR(45) NOT NULL ,
  `ativo` TINYINT(1) NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `i_nome` (`nome` ASC) ,
  INDEX `i_titulo` (`titulo` ASC) ,
  INDEX `i_ativo` (`ativo` ASC) )
ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO sis_modulos (id,nome,titulo,ativo) VALUES (1,'SISTEMA','Sistema',1);

-- -----------------------------------------------------
-- Table `configuracoes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `sis_configuracoes` ;
CREATE  TABLE IF NOT EXISTS `sis_configuracoes` (
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
  PRIMARY KEY (`id`) ,
  INDEX `fk_cidades` (`cidade_id` ASC) )
ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO sis_configuracoes (id,empresa,email,cep,tel1,tel2,celular,cidade_id) VALUES (1,'MOURA INFORMÁTICA','moura@mourainfo.com.br','30575000','3112345678','3187654321','3388345687',2302);
