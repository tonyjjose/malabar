/* MalabarBCC project database creation SQL statements
 *
 * By: Tony J Jose
 * www.33dots.com/
 * https://github.com/tonyjjose
 *
 *
 * Date 4 Feb 2015	
 *
 * version 0.1
 * The first draft of SQL statements
 */


CREATE DATABASE IF NOT EXISTS `bcc_app`;


/* The user data. 
 * User includes, students, instructors and managers
 *
 */
CREATE TABLE IF NOT EXISTS `bcc_app`.`users` (
 `user_id` int(5) NOT NULL AUTO_INCREMENT,
 `user_name` varchar(64) NOT NULL,
 `user_password_hash` varchar(255) DEFAULT NULL,
 `user_email` varchar(64) NOT NULL,
 `user_age` tinyint(2) UNSIGNED NOT NULL, -- 14 to 99 (unsigned means not negative)
 `user_sex` char(1) NOT NULL, -- M or F
 `user_married` varchar(10), -- needed?
 `user_qualification` varchar(20),
 `user_bio` text,
 `user_phone` varchar(15),
 `user_mobile` varchar(15),
 `user_address` varchar(255),
 `user_course_mode` char(1) NOT NULL, -- can be E-email or P-postal
 `user_type` char(1) NOT NULL, -- can be S-student, M-manager, I-instructor
 `user_approved` tinyint(1) NOT NULL DEFAULT '0', -- enrolled users will have to be approved
 `user_active` tinyint(1) NOT NULL DEFAULT '1', -- every users will be active until disabled.
 `user_anonymous` tinyint(1) NOT NULL DEFAULT '0',
 `user_creation_timestamp` bigint(20) DEFAULT NULL, -- should this be datetime or timestamp? or just a bigint?
 `user_last_login_timestamp` bigint(20) DEFAULT NULL, -- should this be datetime or timestamp? or just a bigint?
  PRIMARY KEY (`user_id`),
  UNIQUE (`user_email`), -- note that we will be using email as our loginId
  CHECK (`user_age`>14)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `bcc_app`.`users` AUTO_INCREMENT=101; -- to begin from 101 onwards

/* Our course catogory table. we can use this for categorising courses,
 * as of now, may be in the basis of languages
 *
 */
CREATE TABLE IF NOT EXISTS `bcc_app`.`category` (
`cat_id` int(3) NOT NULL AUTO_INCREMENT,	
`cat_name` varchar (15) NOT NULL,
`cat_desc` varchar(255),
PRIMARY KEY (`cat_id`),
UNIQUE (`cat_name`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `bcc_app`.`category` AUTO_INCREMENT=101;


/* The course details
 * Should we need a shortname for the courses?? --yet to decide
 *
 */
CREATE TABLE IF NOT EXISTS `bcc_app`.`courses` (
`course_id` int(3) NOT NULL AUTO_INCREMENT,
`course_name` varchar (25) NOT NULL,
`course_desc` varchar(255),
`course_active` tinyint(1) NOT NULL DEFAULT '1', -- all are active untill disabled
`course_category_id` int(3) NOT NULL,
PRIMARY KEY (`course_id`),
UNIQUE (`course_name`),
CONSTRAINT `coure_category_fk` FOREIGN KEY (`course_category_id`) REFERENCES `category` (`cat_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `bcc_app`.`courses` AUTO_INCREMENT=101; -- to begin from 101 onwards



/* This table holds the details of the courses each student takes part in.
 * It also includes his instrutor for that course and the status of his course, 
 * whether completed or not.
 *
 */
CREATE TABLE IF NOT EXISTS `bcc_app`.`student_course` (
`student_id` int(5) NOT NULL, 
`course_id` int(3) NOT NULL,
`instructor_id` int(5) NOT NULL, -- the instructor for this course for this student. 	
`course_status`  char(1) NOT NULL DEFAULT 'A', -- A-active, I-inactive, C-completed.
CONSTRAINT `student_id_fk` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`),
CONSTRAINT `instructor_id_fk` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`user_id`),
CONSTRAINT `course_id_fk` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`),
PRIMARY KEY (`student_id`,`course_id`) -- a student cannot take part same course twice.
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/* This is used for logging user activity.
 *
 */
CREATE TABLE IF NOT EXISTS `bcc_app`.`activity` ( 
`activity_id` int(10) NOT NULL AUTO_INCREMENT,
`activity_name` varchar (20) NOT NULL ,  -- we will define some constants in php
`activity_user_id` int(5) NOT NULL,
`activity_time` datetime NOT NULL, -- should this be datetime or timestamp? or just a bigint?
PRIMARY KEY (`activity_id`),
CONSTRAINT `user_id_fk` FOREIGN KEY (`activity_user_id`) REFERENCES `users` (`user_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;