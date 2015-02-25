/* MalabarBCC project database update SQL statements
 *
 * By: Tony J Jose
 * www.33dots.com/
 * https://github.com/tonyjjose
 *
 *
 * Date 24 Feb 2015	
 *
 * version 0.1
 * Some updates to the db. we change the timestamps to datetime format. 
 * so that we can use NOW() function sql while INSERT and UPDATE
 */

# for mysql 5.6.5 and later
 ALTER TABLE `users` MODIFY COLUMN `user_creation_timestamp` datetime DEFAULT CURRENT_TIMESTAMP; #supports CURRENT_TIMESTAMP
 ALTER TABLE `users` MODIFY COLUMN `user_last_login_timestamp` datetime DEFAULT CURRENT_TIMESTAMP; #supports CURRENT_TIMESTAMP
 ALTER TABLE `activity` ALTER COLUMN `activity_time` SET DEFAULT CURRENT_TIMESTAMP #just alter table to add new default

# for mysql 5.6 and lower. this version doesnt support CURRENT_DATETIME
 ALTER TABLE `users` MODIFY COLUMN `user_creation_timestamp` datetime NOT NULL; #supports CURRENT_TIMESTAMP
 ALTER TABLE `users` MODIFY COLUMN `user_last_login_timestamp` datetime DEFAULT NULL; #supports CURRENT_TIMESTAMP
