-- MySQL Script generated by MySQL Workbench
-- Thu Jan 28 01:27:29 2021
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema alium
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema alium
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `alium` DEFAULT CHARACTER SET utf8 ;
USE `alium` ;

-- -----------------------------------------------------
-- Table `alium`.`usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `alium`.`usuario` ;

CREATE TABLE IF NOT EXISTS `alium`.`usuario` (
  `id_usuario` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(60) NOT NULL,
  `cpf_cnpj` VARCHAR(14) NOT NULL,
  `email` VARCHAR(70) NOT NULL,
  `telefone` VARCHAR(45) NOT NULL,
  `descricao` MEDIUMTEXT NULL,
  `foto_perfil` MEDIUMTEXT NULL,
  `logradouro` VARCHAR(45) NULL,
  `num_casa` VARCHAR(45) NULL,
  `bairro` VARCHAR(45) NULL,
  `municipio` VARCHAR(45) NULL,
  `estado` VARCHAR(45) NULL,
  `password` VARCHAR(45) NOT NULL,
  `username` VARCHAR(70) NOT NULL,
  `cep` INT NULL,
  `role` VARCHAR(45) NULL,
  `redes_sociais` VARCHAR(255) NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE INDEX `cpf_cnpj_UNIQUE` (`cpf_cnpj` ASC),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `alium`.`tipo_servico`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `alium`.`tipo_servico` ;

CREATE TABLE IF NOT EXISTS `alium`.`tipo_servico` (
  `id_tp_servico` INT NOT NULL AUTO_INCREMENT,
  `tipo_servico` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id_tp_servico`),
  UNIQUE INDEX `tipo_servico_UNIQUE` (`tipo_servico` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alium`.`servico`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `alium`.`servico` ;

CREATE TABLE IF NOT EXISTS `alium`.`servico` (
  `id_servico` INT NOT NULL AUTO_INCREMENT,
  `data_inicio` DATE NOT NULL,
  `data_fim` DATE NOT NULL,
  `id_tp_servico` INT NOT NULL,
  PRIMARY KEY (`id_servico`),
  INDEX `fk_servico_tipo_servico1_idx` (`id_tp_servico` ASC),
  CONSTRAINT `fk_servico_tipo_servico1`
    FOREIGN KEY (`id_tp_servico`)
    REFERENCES `alium`.`tipo_servico` (`id_tp_servico`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alium`.`feedback`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `alium`.`feedback` ;

CREATE TABLE IF NOT EXISTS `alium`.`feedback` (
  `id_feedback` INT NOT NULL AUTO_INCREMENT,
  `feedback` VARCHAR(200) NOT NULL,
  `avaliacao` INT NOT NULL,
  `titulo` VARCHAR(45) NOT NULL,
  `id_servico` INT NOT NULL,
  `id_usuario` INT NOT NULL,
  PRIMARY KEY (`id_feedback`),
  INDEX `fk_feedback_servico1_idx` (`id_servico` ASC),
  INDEX `fk_feedback_usuario1_idx` (`id_usuario` ASC),
  CONSTRAINT `fk_feedback_servico1`
    FOREIGN KEY (`id_servico`)
    REFERENCES `alium`.`servico` (`id_servico`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_feedback_usuario1`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `alium`.`usuario` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alium`.`imagem`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `alium`.`imagem` ;

CREATE TABLE IF NOT EXISTS `alium`.`imagem` (
  `id_imagem` INT NOT NULL AUTO_INCREMENT,
  `url` MEDIUMTEXT NOT NULL,
  `id_usuario` INT NOT NULL,
  `id_tp_servico` INT NOT NULL,
  PRIMARY KEY (`id_imagem`),
  INDEX `fk_imagem_usuario_idx` (`id_usuario` ASC),
  INDEX `fk_imagem_tipo_servico1_idx` (`id_tp_servico` ASC),
  CONSTRAINT `fk_imagem_usuario`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `alium`.`usuario` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_imagem_tipo_servico1`
    FOREIGN KEY (`id_tp_servico`)
    REFERENCES `alium`.`tipo_servico` (`id_tp_servico`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alium`.`servico_has_usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `alium`.`servico_has_usuario` ;

CREATE TABLE IF NOT EXISTS `alium`.`servico_has_usuario` (
  `id_servico` INT NOT NULL,
  `id_usuario` INT NOT NULL,
  PRIMARY KEY (`id_servico`, `id_usuario`),
  INDEX `fk_servico_has_usuario_usuario1_idx` (`id_usuario` ASC),
  INDEX `fk_servico_has_usuario_servico1_idx` (`id_servico` ASC),
  CONSTRAINT `fk_servico_has_usuario_servico1`
    FOREIGN KEY (`id_servico`)
    REFERENCES `alium`.`servico` (`id_servico`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_servico_has_usuario_usuario1`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `alium`.`usuario` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
