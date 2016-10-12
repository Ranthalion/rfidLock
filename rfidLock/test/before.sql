-- This needs to be run in order for mysql tests to run properly
CREATE USER 'test'@'localhost' IDENTIFIED BY '8CwfvJmQb5x3mLadU29gc9CmqLtnMS3f';
CREATE DATABASE test_database;
GRANT ALL ON test_database.* TO 'test'@'localhost';
