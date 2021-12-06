DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_user`(IN `in_name` TEXT CHARSET utf8, IN `in_pass` TEXT CHARSET utf8, IN `in_email` TEXT CHARSET utf8)
BEGIN
DECLARE
new_user TEXT;
INSERT INTO `testdb`.`user` (`name`, `email`) VALUES(in_name, in_email);
SELECT CONCAT('user_',LAST_INSERT_ID()) INTO new_user;
PREPARE add_role FROM CONCAT('GRANT SELECT, UPDATE ON `testdb`.`user` TO ',new_user,' IDENTIFIED BY ''',in_pass,'''');
EXECUTE add_role;
SELECT LAST_INSERT_ID();
END$$
DELIMITER ;