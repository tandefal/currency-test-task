CREATE TABLE `currency_rates`
(
    `id`            int(11)        NOT NULL AUTO_INCREMENT,
    `currency_code` varchar(10)    NOT NULL,
    `num_code`      int(3)         NOT NULL,
    `char_code`     varchar(5)     NOT NULL,
    `nominal`       int(11)        NOT NULL,
    `name`          varchar(255)   NOT NULL,
    `value`         decimal(10, 4) NOT NULL,
    `vunit_rate`    decimal(10, 4) NOT NULL,
    `created_at`    timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `currency_code` (`currency_code`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;