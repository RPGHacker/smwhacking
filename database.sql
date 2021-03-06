SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `awarded_medals` (
  `user`       INT(11) NOT NULL,
  `medal`      INT(11) NOT NULL,
  `award_time` INT(11) NOT NULL,
  `favorite`   INT(11) NOT NULL DEFAULT '0'
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `chat_messages` (
  `id`        INT(11)    NOT NULL,
  `author`    INT(11)    NOT NULL,
  `post_time` INT(11)    NOT NULL,
  `content`   TEXT       NOT NULL,
  `deleted`   TINYINT(1) NOT NULL DEFAULT '0'
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `edits` (
  `post`      INT(11) NOT NULL,
  `user`      INT(11) NOT NULL,
  `edit_time` INT(11) NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `files` (
  `id`                INT(11)       NOT NULL,
  `user`              INT(11)       NOT NULL,
  `name`              VARCHAR(50)   NOT NULL,
  `extension`         VARCHAR(10)   NOT NULL,
  `short_description` VARCHAR(50)   NOT NULL,
  `long_description`  VARCHAR(1000) NOT NULL,
  `upload_time`       INT(11)       NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `forums` (
  `id`                INT(11)      NOT NULL,
  `category`          INT(11)      NOT NULL,
  `name`              VARCHAR(255) NOT NULL,
  `description`       MEDIUMTEXT   NOT NULL,
  `threads`           INT(11)      NOT NULL,
  `posts`             INT(11)      NOT NULL,
  `last_post`         INT(11)      NOT NULL,
  `sort_order`        INT(11)      NOT NULL,
  `view_powerlevel`   INT(11)      NOT NULL,
  `post_powerlevel`   INT(11)      NOT NULL,
  `thread_powerlevel` INT(11)      NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `forum_categories` (
  `id`         INT(11)      NOT NULL,
  `name`       VARCHAR(255) NOT NULL,
  `sort_order` INT(11)      NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `medals` (
  `id`              INT(11)                                                NOT NULL,
  `category`        INT(11)                                                NOT NULL,
  `name`            VARCHAR(255)                                           NOT NULL,
  `description`     MEDIUMTEXT                                             NOT NULL,
  `image_filename`  VARCHAR(128)                                           NOT NULL,
  `award_condition` ENUM ('manual', 'post_count', 'registration_time', '') NOT NULL,
  `value`           INT(11)                                                NOT NULL,
  `secret`          TINYINT(1)                                             NOT NULL DEFAULT '0'
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `medal_categories` (
  `id`   INT(11)      NOT NULL,
  `name` VARCHAR(255) NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `posts` (
  `id`        INT(11)    NOT NULL,
  `thread`    INT(11)    NOT NULL,
  `author`    INT(11)    NOT NULL,
  `post_time` INT(11)    NOT NULL,
  `content`   MEDIUMTEXT NOT NULL,
  `deleted`   TINYINT(1) NOT NULL DEFAULT '0'
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `private_messages` (
  `id`        INT(11)    NOT NULL,
  `send_time` INT(11)    NOT NULL,
  `author`    INT(11)    NOT NULL,
  `recipient` INT(11)    NOT NULL,
  `subject`   MEDIUMTEXT NOT NULL,
  `content`   MEDIUMTEXT NOT NULL,
  `unread`    TINYINT(1) NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `ranks` (
  `id`        INT(11)      NOT NULL,
  `name`      VARCHAR(255) NOT NULL,
  `min_posts` INT(11)      NOT NULL,
  `has_image` TINYINT(1)   NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `secrets` (
  `id`      INT(11)      NOT NULL,
  `name`    VARCHAR(511) NOT NULL,
  `is_link` TINYINT(1)   NOT NULL,
  `content` MEDIUMTEXT   NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `smileys` (
  `id`             INT(10) UNSIGNED NOT NULL,
  `code`           VARCHAR(50)      NOT NULL DEFAULT '',
  `name`           VARCHAR(100)     NOT NULL,
  `image_filename` VARCHAR(100)     NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `threads` (
  `id`             INT(11)      NOT NULL,
  `forum`          INT(11)      NOT NULL,
  `name`           VARCHAR(511) NOT NULL,
  `creation_time`  INT(11)      NOT NULL,
  `posts`          INT(11)      NOT NULL,
  `last_post`      INT(11)      NOT NULL,
  `last_post_time` INT(11)      NOT NULL,
  `views`          INT(11)      NOT NULL,
  `closed`         TINYINT(1)   NOT NULL,
  `sticky`         TINYINT(1)   NOT NULL,
  `deleted`        TINYINT(1)   NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `threads_read` (
  `user`           INT(11) NOT NULL,
  `thread`         INT(11) NOT NULL,
  `last_read_time` INT(11) NOT NULL
  COMMENT 'Post-Zeit des letzten gelesenen Posts'
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `users` (
  `id`                   INT(11)                 NOT NULL,
  `name`                 VARCHAR(255)
                         CHARACTER SET utf8      NOT NULL,
  `password`             TEXT CHARACTER SET utf8 NOT NULL,
  `legacy_login`         TINYINT(1)              NOT NULL
  COMMENT 'ist der Passwort-Hash noch im alten phpBB-Format gespeichert?',
  `email`                VARCHAR(100)
                         CHARACTER SET utf8      NOT NULL,
  `powerlevel`           INT(11)                 NOT NULL
  COMMENT '0 = normaler User, 1 = Mod, 2 = Admin',
  `title`                VARCHAR(255)            NOT NULL,
  `bio`                  TEXT                    NOT NULL,
  `signature`            TEXT                    NOT NULL,
  `location`             VARCHAR(100)            NOT NULL,
  `website`              VARCHAR(100)
                         CHARACTER SET utf8      NOT NULL,
  `theme`                ENUM ('default', 'dark')
                         CHARACTER SET utf8      NOT NULL DEFAULT 'default',
  `enable_notifications` TINYINT(1)              NOT NULL,
  `show_chat_bar`        TINYINT(1)              NOT NULL DEFAULT '1',
  `chat_key_behavior`    ENUM ('enter-to-send', 'ctrl-enter-to-send')
                         CHARACTER SET utf8      NOT NULL DEFAULT 'ctrl-enter-to-send',
  `registration_time`    INT(11)                 NOT NULL,
  `last_login_time`      INT(11)                 NOT NULL,
  `last_activity_time`   INT(11)                          DEFAULT NULL,
  `banned`               TINYINT(1)              NOT NULL,
  `activated`            TINYINT(1)              NOT NULL
  COMMENT 'Registrierung per E-Mail abgeschlossen',
  `activation_token`     VARCHAR(32)
                         CHARACTER SET utf8      NOT NULL,
  `csrf_token`           VARCHAR(16)
                         CHARACTER SET utf8      NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `watched_threads` (
  `user`   INT(11) NOT NULL,
  `thread` INT(11) NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;


ALTER TABLE `awarded_medals`
  ADD UNIQUE KEY `user` (`user`, `medal`);

ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `forums`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `forum_categories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `medals`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `medal_categories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author` (`author`, `thread`);

ALTER TABLE `private_messages`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ranks`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `secrets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`(191));

ALTER TABLE `smileys`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `threads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `last_post_time` (`last_post_time`),
  ADD KEY `sticky` (`sticky`, `deleted`),
  ADD KEY `name` (`name`(191), `posts`, `last_post`, `views`);

ALTER TABLE `threads_read`
  ADD PRIMARY KEY (`user`, `thread`),
  ADD UNIQUE KEY `user` (`user`, `thread`),
  ADD KEY `last_read_time` (`last_read_time`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `location` (`location`, `website`);

ALTER TABLE `watched_threads`
  ADD UNIQUE KEY `thread` (`thread`, `user`);


ALTER TABLE `chat_messages`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `files`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `forums`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `forum_categories`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `medals`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `medal_categories`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `posts`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `private_messages`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `ranks`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `secrets`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `smileys`
  MODIFY `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `threads`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `users`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
