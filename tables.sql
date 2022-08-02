CREATE TABLE `users` (
	`acc_id` int NOT NULL AUTO_INCREMENT,
	`email` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`password` VARCHAR(75) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`creation_time` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`creation_ip` INT(28),
	PRIMARY KEY (acc_id)
);
CREATE TABLE `login_attempts` (
	`acc_id` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`is_successful` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`login_time` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`login_ip` INT(28)
);
