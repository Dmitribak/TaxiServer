CREATE TABLE
  `id_status_users` (
  `id_status` INT NOT NULL AUTO_INCREMENT,
  `status_name` VARCHAR(20),
  PRIMARY KEY (`id_status`)
);
CREATE TABLE
  `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `phone_users` CHAR(10) NOT NULL,
  `password` VARCHAR(40) NOT NULL,
  `sms_pas` VARCHAR(10) NOT NULL,
  `id_status_users` TINYINT NOT NULL,
  `name_user` VARCHAR(25),
  `family_users` VARCHAR(30),
  `date_birthday` DATE,
  `email_users` VARCHAR(50),
  `referal_from` VARCHAR(20),
  `referal_My` VARCHAR(20),
  FOREIGN KEY (id_status_users) REFERENCES `id_status_users`(id_status),
  PRIMARY KEY(`id`)
);