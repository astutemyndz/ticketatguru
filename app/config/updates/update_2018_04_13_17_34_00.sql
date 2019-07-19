
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'front_available_seats', 'frontend', 'Label / Available seats', 'script', '2017-08-18 04:02:23');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Available seats', 'script');

COMMIT;