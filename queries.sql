-- truncate tables
TRUNCATE TABLE `artisans`;
TRUNCATE TABLE `artisan_categories`;
TRUNCATE TABLE `artisan_groups`;
TRUNCATE TABLE `artisan_linked_categories`;
TRUNCATE TABLE `artisan_ratings`;
TRUNCATE TABLE `artisan_signins`;
TRUNCATE TABLE `email_logs`;
TRUNCATE TABLE `estates`;
TRUNCATE TABLE `events`;
TRUNCATE TABLE `goods`;
TRUNCATE TABLE `good_buy_items`;
TRUNCATE TABLE `good_items`;
TRUNCATE TABLE `groups`;
TRUNCATE TABLE `messages`;
TRUNCATE TABLE `next_of_kins`;
TRUNCATE TABLE `notifications`;
TRUNCATE TABLE `pages`;
TRUNCATE TABLE `payments`;
TRUNCATE TABLE `power_products`;
TRUNCATE TABLE `products`;
TRUNCATE TABLE `product_installments`;
TRUNCATE TABLE `purchased_products`;
TRUNCATE TABLE `send_mes`;
TRUNCATE TABLE `send_me_buy_items`;
TRUNCATE TABLE `send_me_estate_items`;
TRUNCATE TABLE `send_me_items`;
TRUNCATE TABLE `sms_logs`;
TRUNCATE TABLE `user_checks`;
TRUNCATE TABLE `visitors`;
TRUNCATE TABLE `visitor_settings`;
TRUNCATE TABLE `visitor_visits`;


________________2021/04/13_____________
ALTER TABLE `users` DROP INDEX `users_email_unique`;
ALTER TABLE `users` ADD UNIQUE(`email`);
ALTER TABLE `adverts` ADD `estate_id` INT(11) NULL DEFAULT NULL AFTER `created_id`;
UPDATE `routes` SET `is_estate_manager` = '1' WHERE `routes`.`id` IN (100,101,102,103,104,105);
ALTER TABLE `adverts` ADD `external_url` VARCHAR(255) NULL DEFAULT NULL AFTER `photo`;
ALTER TABLE `artisans`  ADD `email` VARCHAR(255) NULL DEFAULT NULL  AFTER `name`;
ALTER TABLE `artisans`  ADD `bvn` VARCHAR(255) NULL DEFAULT NULL  AFTER `address`,  ADD `nin` VARCHAR(255) NULL DEFAULT NULL  AFTER `bvn`;