-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Table `mm_smartcloud_sistema`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mm_smartcloud_sistema` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(40) CHARACTER SET 'utf8' COLLATE 'utf8' NOT NULL,
  `ativo` INT(1) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8
COMMENT = 'Tabela de sistemas ao qual um usuário pode pertencer';


-- -----------------------------------------------------
-- Table `mm_smartcloud_acl`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mm_smartcloud_acl` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8' NOT NULL,
  `idfk_sistema` INT(10) UNSIGNED NOT NULL,
  `descricao` VARCHAR(200) CHARACTER SET 'utf8' COLLATE 'utf8' NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_mm_smartcloud_acl_mm_smartcloud_sistema1_idx` (`idfk_sistema` ASC),
  CONSTRAINT `fk_mm_smartcloud_acl_mm_smartcloud_sistema1`
    FOREIGN KEY (`idfk_sistema`)
    REFERENCES `mm_smartcloud_sistema` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8
COMMENT = 'Cadastro de ACL para roles\r\n';


-- -----------------------------------------------------
-- Table `mm_smartcloud_role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mm_smartcloud_role` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(40) CHARACTER SET 'latin1' NOT NULL,
  `tipo` INT(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8
COMMENT = 'Roles permitidos no MM SmartCloud';


-- -----------------------------------------------------
-- Table `mm_smartcloud_role_acl`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mm_smartcloud_role_acl` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idfk_role` INT(10) UNSIGNED NOT NULL,
  `idfk_acl` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_mm_smartcloud_role_acl_mm_smartcloud_role` (`idfk_role` ASC),
  INDEX `fk_mm_smartcloud_role_acl_mm_smartcloud_acl` (`idfk_acl` ASC),
  CONSTRAINT `fk_mm_smartcloud_role_acl_mm_smartcloud_acl`
    FOREIGN KEY (`idfk_acl`)
    REFERENCES `mm_smartcloud_acl` (`id`)
    ON UPDATE CASCADE,
  CONSTRAINT `fk_mm_smartcloud_role_acl_mm_smartcloud_role`
    FOREIGN KEY (`idfk_role`)
    REFERENCES `mm_smartcloud_role` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8
COMMENT = 'Vinculo entre ACLs e Roles';


-- -----------------------------------------------------
-- Table `mm_smartcloud_usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mm_smartcloud_usuario` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(200) CHARACTER SET 'latin1' NOT NULL,
  `email` VARCHAR(200) CHARACTER SET 'latin1' NOT NULL,
  `senha` VARCHAR(40) CHARACTER SET 'latin1' NOT NULL,
  `thumb` VARCHAR(200) CHARACTER SET 'utf8' COLLATE 'utf8' NULL DEFAULT '/assets/img/sem-foto.png',
  `token` VARCHAR(40) CHARACTER SET 'latin1' NULL DEFAULT NULL COMMENT 'Token usado para alteração de senha',
  `ativo` TINYINT(1) UNSIGNED NOT NULL,
  `ultimo_acesso` DATETIME NULL DEFAULT NULL,
  `data_criacao` TIMESTAMP NULL DEFAULT NULL,
  `data_alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `idfk_role` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email` (`email` ASC),
  INDEX `token` (`token` ASC),
  INDEX `fk_mm_smartcloud_usuario_mm_smartcloud_role1_idx` (`idfk_role` ASC),
  CONSTRAINT `fk_mm_smartcloud_usuario_mm_smartcloud_role1`
    FOREIGN KEY (`idfk_role`)
    REFERENCES `mm_smartcloud_role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8
COMMENT = 'Tabela de usuários MM SmartCloud';


-- -----------------------------------------------------
-- Table `mm_smartcloud_usuario_acl`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mm_smartcloud_usuario_acl` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` INT(10) UNSIGNED NOT NULL,
  `acl_id` INT(10) UNSIGNED NOT NULL,
  `usuario` INT(10) NULL DEFAULT NULL COMMENT 'usuário que deu a permissão',
  PRIMARY KEY (`id`),
  INDEX `fk_mm_usuario_acl_mm_smartcloud_usuario1_idx` (`usuario_id` ASC),
  INDEX `fk_mm_usuario_acl_mm_smartcloud_acl1_idx` (`acl_id` ASC),
  CONSTRAINT `fk_mm_usuario_acl_mm_smartcloud_acl1`
    FOREIGN KEY (`acl_id`)
    REFERENCES `mm_smartcloud_acl` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_mm_usuario_acl_mm_smartcloud_usuario1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `mm_smartcloud_usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `mm_smartcloud_usuario_acl_rest`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mm_smartcloud_usuario_acl_rest` (
  `id` INT NOT NULL,
  `id_acl` INT NOT NULL,
  `id_usuario` INT NOT NULL DEFAULT 1,
  `post` TINYINT(1) NULL DEFAULT 1,
  `put` TINYINT(1) NULL DEFAULT 1,
  `get` TINYINT(1) NULL DEFAULT 1,
  `delete` TINYINT(1) NULL DEFAULT 1,
  `patch` TINYINT(1) NULL DEFAULT 1,
  PRIMARY KEY (`id`, `id_acl`, `id_usuario`),
  INDEX `fk_mm_smartcloud_usuario_acl_rest_mm_smartcloud_usuario_acl_idx` (`id_usuario` ASC),
  INDEX `fk_mm_smartcloud_usuario_acl_rest_mm_smartcloud_usuario_acl_idx1` (`id_acl` ASC),
  CONSTRAINT `fk_mm_smartcloud_usuario_acl_rest_mm_smartcloud_usuario_acl1`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `mm_smartcloud_usuario_acl` (`usuario_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_mm_smartcloud_usuario_acl_rest_mm_smartcloud_usuario_acl2`
    FOREIGN KEY (`id_acl`)
    REFERENCES `mm_smartcloud_usuario_acl` (`acl_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
