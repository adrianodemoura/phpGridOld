-- -----------------------------------------------------
-- Table `mydb`.`Pacientes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `hem_pacientes` ;
CREATE TABLE IF NOT EXISTS `hem_pacientes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(60) NOT NULL,
  `email` VARCHAR(60) NOT NULL,
  `dt_nascimento` DATE NOT NULL,
  `cidade_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`) ,
  INDEX `i_nome` (`nome` ASC) ,
  INDEX `i_email` (`email` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hem_controles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `hem_controles` ;
CREATE TABLE IF NOT EXISTS `hem_controles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `dt_aplicacao` DATETIME NOT NULL,

  `retirada_qtd` INT(11) NOT NULL,
  `retirada_loc` VARCHAR(50) NOT NULL,

  `local_qtd` INT(11) NOT NULL,
  `local_apl` VARCHAR(50) NOT NULL,

  `paciente_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_controle_paciente` FOREIGN KEY (`paciente_id`) REFERENCES `hem_pacientes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;
CREATE INDEX `fk_controle_paciente_idx` ON `hem_controles` (`paciente_id` ASC);


-- -----------------------------------------------------
-- Table `hem_aplicadores`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `hem_aplicadores` ;
CREATE TABLE IF NOT EXISTS `hem_aplicadores` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(60) NOT NULL,
  `email` VARCHAR(60) NULL,
  `telefone` VARCHAR(11) NULL,
  `cidade_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`) ,
  INDEX `i_nome` (`nome` ASC) ,
  INDEX `i_email` (`email` ASC) ,
  INDEX `i_cidade_id` (`cidade_id` ASC) )
ENGINE = InnoDB;

