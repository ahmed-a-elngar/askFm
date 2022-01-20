# askFm

## before running code

### create database named ask 
CREATE DATABASE ask;

### then create users table
CREATE TABLE users(
    user_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(50) NOT NULL,
    user_email VARCHAR(100) NOT NULL,
    user_pass VARCHAR(100) NOT NULL,
    user_full_name VARCHAR(50) DEFAULT 'No Name',
    user_DB DATE NOT NULL,
    user_gendar VARCHAR(7) DEFAULT 'male',
    user_pic VARCHAR(255) DEFAULT 'pics/avatar-01.jpg',
    user_bg VARCHAR(255) DEFAULT 'pics/bg-01.jpg',
    user_mood VARCHAR(255),
    user_bio VARCHAR(500),
    user_location VARCHAR(100),
    user_web VARCHAR(500),
    user_interests VARCHAR(255),
    user_permissions VARCHAR(7) DEFAULT '0,0,0,0',
    user_status TINYINT(1) DEFAULT '0',
    user_l_count INT(11) DEFAULT '0',
    user_c_count INT(11) DEFAULT '0',
    user_today_c_count INT(7) DEFAULT '0',
    user_weekly_c_count INT(7) DEFAULT '0',
    user_f_count INT(11) DEFAULT '0'
);