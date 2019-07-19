
START TRANSACTION;

INSERT INTO `options` (`foreign_id`, `key`, `tab_id`, `value`, `label`, `type`, `order`, `is_visible`, `style`) VALUES
(1, 'o_email_address', 1, NULL, NULL, 'string', 7, 1, NULL);

INSERT INTO `fields` VALUES (NULL, 'opt_o_email_address', 'backend', 'Options / Email account for email notifications', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Email account for email notifications', 'script');

INSERT INTO `fields` VALUES (NULL, 'lblOptionEmailTip', 'backend', 'Label / Email tooltip', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Set the email which users will see when they receive emails from the system. Go to Notifications tab to manage automated email notifications.', 'script');

COMMIT;