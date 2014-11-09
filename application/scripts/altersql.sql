Use of uninitialized value in pattern match (m//) at /usr/local/share/perl/5.14.2/MySQL/Diff.pm line 371.
## mysqldiff 0.43
## 
## Run on Sun Nov 10 13:03:31 2013
## Options: password=b3v9Ww8-Y0cZw9RdyJQq, user=root, debug=0
##
## ---   db: db1 (user=root)
## +++   db: db (user=root)

ALTER TABLE users DROP COLUMN id; # was int(11) NOT NULL AUTO_INCREMENT
ALTER TABLE users ADD COLUMN salt varchar(100) NOT NULL;
ALTER TABLE users ADD COLUMN userid int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE users ADD COLUMN fname varchar(50) DEFAULT NULL;
ALTER TABLE users ADD COLUMN labour_resource_id int(11) DEFAULT NULL;
ALTER TABLE users ADD COLUMN modified int(11) DEFAULT NULL;
ALTER TABLE users ADD COLUMN state enum('signed up','verified','deleted') DEFAULT 'signed up';
ALTER TABLE users ADD COLUMN username varchar(20) NOT NULL;
ALTER TABLE users ADD COLUMN created int(11) DEFAULT NULL;
ALTER TABLE users ADD COLUMN email varchar(50) DEFAULT NULL;
ALTER TABLE users ADD COLUMN password varchar(100) NOT NULL;
ALTER TABLE users ADD COLUMN lname varchar(50) DEFAULT NULL;
ALTER TABLE users ADD COLUMN roleid int(11) NOT NULL;
ALTER TABLE users DROP PRIMARY KEY; # was (id)
ALTER TABLE users ADD PRIMARY KEY (userid);
