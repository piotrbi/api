CREATE DATABASE IF NOT EXISTS `api_test`;

CREATE USER 'api_test'@'%' IDENTIFIED BY 'pass_test';
GRANT ALL ON *.* TO 'api_test'@'%' identified by 'pass_test';

FLUSH PRIVILEGES;
