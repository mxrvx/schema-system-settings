DROP TABLE IF EXISTS `context`;
DROP TABLE IF EXISTS `system_settings`;
DROP TABLE IF EXISTS `context_setting`;
DROP TABLE IF EXISTS `access_context`;
DROP TABLE IF EXISTS `deprecated_method`;
DROP TABLE IF EXISTS `access_policies`;
DROP TABLE IF EXISTS `site_content`;

CREATE TABLE `context`
(
    `key`         VARCHAR(100) NOT NULL PRIMARY KEY,
    `name`        VARCHAR(191) NULL,
    `description` TINYTEXT     NULL,
    `rank`        INT(11)      NOT NULL DEFAULT 0,
    INDEX `name` (`name`),
    INDEX `rank` (`rank`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `system_settings`
(
    `key`       VARCHAR(50)  NOT NULL PRIMARY KEY,
    `value`     TEXT         NOT NULL,
    `xtype`     VARCHAR(75)  NOT NULL DEFAULT 'textfield',
    `namespace` VARCHAR(40)  NOT NULL DEFAULT 'core',
    `area`      VARCHAR(255) NOT NULL DEFAULT '',
    `editedon`  TIMESTAMP    NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `context_setting`
(
    `context_key` VARCHAR(191) NOT NULL,
    `key`         VARCHAR(50)  NOT NULL,
    `value`       MEDIUMTEXT,
    `xtype`       VARCHAR(75)  NOT NULL DEFAULT 'textfield',
    `namespace`   VARCHAR(40)  NOT NULL DEFAULT 'core',
    `area`        VARCHAR(255) NOT NULL DEFAULT '',
    `editedon`    TIMESTAMP    NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`context_key`, `key`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;


CREATE TABLE `access_context`
(
    `id`              INT UNSIGNED     NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `target`          VARCHAR(100)     NOT NULL DEFAULT '',
    `principal_class` VARCHAR(100)     NOT NULL DEFAULT 'MODX\Revolution\modPrincipal',
    `principal`       INT(10) UNSIGNED NOT NULL DEFAULT 0,
    `authority`       INT(10) UNSIGNED NOT NULL DEFAULT 9999,
    `policy`          INT(10) UNSIGNED NOT NULL DEFAULT 0,
    INDEX `target` (`target`),
    INDEX `principal_class` (`principal_class`),
    INDEX `principal` (`principal`),
    INDEX `authority` (`authority`),
    INDEX `policy` (`policy`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;


CREATE TABLE `deprecated_method`
(
    `id`             INT UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `definition`     VARCHAR(191)  NOT NULL DEFAULT '',
    `since`          VARCHAR(191)  NOT NULL DEFAULT '',
    `recommendation` VARCHAR(1024) NOT NULL DEFAULT '',
    INDEX `definition` (`definition`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `access_policies`
(
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name`        VARCHAR(191) NOT NULL,
    `description` MEDIUMTEXT,
    `parent`      INT UNSIGNED NOT NULL DEFAULT 0,
    `template`    INT UNSIGNED NOT NULL DEFAULT 0,
    `class`       VARCHAR(191) NOT NULL DEFAULT '',
    `data`        JSON,
    `lexicon`     VARCHAR(255) NOT NULL DEFAULT 'permissions',
    UNIQUE INDEX `name` (`name`),
    INDEX `parent` (`parent`),
    INDEX `class` (`class`),
    INDEX `template` (`template`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `site_content`
(
    `id`                    INT UNSIGNED        NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `type`                  VARCHAR(20)         NOT NULL DEFAULT 'document',
    `pagetitle`             VARCHAR(191)        NOT NULL DEFAULT '',
    `longtitle`             VARCHAR(191)        NOT NULL DEFAULT '',
    `description`           TEXT,
    `alias`                 VARCHAR(191),
    `link_attributes`       VARCHAR(255)        NOT NULL DEFAULT '',
    `published`             TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    `pub_date`              INT(20)             NOT NULL DEFAULT 0,
    `unpub_date`            INT(20)             NOT NULL DEFAULT 0,
    `parent`                INT(10) UNSIGNED    NOT NULL DEFAULT 0,
    `isfolder`              TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    `introtext`             TEXT,
    `content`               MEDIUMTEXT,
    `richtext`              TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
    `template`              INT(10)             NOT NULL DEFAULT 0,
    `menuindex`             INT(10)             NOT NULL DEFAULT 0,
    `searchable`            TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
    `cacheable`             TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
    `createdby`             INT(10)             NOT NULL DEFAULT 0,
    `createdon`             INT(20)             NOT NULL DEFAULT 0,
    `editedby`              INT(10)             NOT NULL DEFAULT 0,
    `editedon`              INT(20)             NOT NULL DEFAULT 0,
    `deleted`               TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    `deletedon`             INT(20)             NOT NULL DEFAULT 0,
    `deletedby`             INT(10)             NOT NULL DEFAULT 0,
    `publishedon`           INT(20)             NOT NULL DEFAULT 0,
    `publishedby`           INT(10)             NOT NULL DEFAULT 0,
    `menutitle`             VARCHAR(255)        NOT NULL DEFAULT '',
    `content_dispo`         TINYINT(1)          NOT NULL DEFAULT 0,
    `hidemenu`              TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    `class_key`             VARCHAR(100)        NOT NULL DEFAULT 'MODX\Revolution\modDocument',
    `context_key`           VARCHAR(100)        NOT NULL DEFAULT 'web',
    `content_type`          INT(11) UNSIGNED    NOT NULL DEFAULT 1,
    `uri`                   TEXT,
    `uri_override`          TINYINT(1)          NOT NULL DEFAULT 0,
    `hide_children_in_tree` TINYINT(1)          NOT NULL DEFAULT 0,
    `show_in_tree`          TINYINT(1)          NOT NULL DEFAULT 1,
    `properties`            MEDIUMTEXT,
    `alias_visible`         TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,

    INDEX `alias` (`alias`(191)),
    INDEX `published` (`published`),
    INDEX `pub_date` (`pub_date`),
    INDEX `unpub_date` (`unpub_date`),
    INDEX `parent` (`parent`),
    INDEX `isfolder` (`isfolder`),
    INDEX `template` (`template`),
    INDEX `menuindex` (`menuindex`),
    INDEX `searchable` (`searchable`),
    INDEX `cacheable` (`cacheable`),
    INDEX `hidemenu` (`hidemenu`),
    INDEX `class_key` (`class_key`),
    INDEX `context_key` (`context_key`),
    INDEX `uri` (`uri`(191)),
    INDEX `uri_override` (`uri_override`),
    INDEX `hide_children_in_tree` (`hide_children_in_tree`),
    INDEX `show_in_tree` (`show_in_tree`),
    INDEX `cache_refresh_idx` (`parent`, `menuindex`, `id`),
    FULLTEXT INDEX `content_ft_idx` (`pagetitle`, `longtitle`, `description`, `introtext`, `content`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
