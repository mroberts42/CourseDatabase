/*uploads CSV files to database*/


LOAD DATA LOCAL
INFILE 'course.csv' 
REPLACE
INTO TABLE Course
FIELDS TERMINATED BY ','	
;


/*Gets foreign key check error on 2nd field, when error checking turned off, it doesn't upload properly*/

LOAD DATA LOCAL
INFILE 'Prerequisites.csv' 
REPLACE
INTO TABLE Prerequisites
FIELDS TERMINATED BY ','	
IGNORE 1 LINES
;

ALTER TABLE Prerequisites DROP Arb;

LOAD DATA LOCAL
INFILE 'MajorMinor.csv' 
REPLACE
INTO TABLE MajorMinor
FIELDS TERMINATED BY ','	
IGNORE 1 LINES
;

LOAD DATA LOCAL
INFILE 'Required.csv' 
REPLACE
INTO TABLE Required
FIELDS TERMINATED BY ','	
IGNORE 1 LINES
;

LOAD DATA LOCAL
INFILE 'Buckets.csv' 
REPLACE
INTO TABLE Buckets
FIELDS TERMINATED BY ','	
IGNORE 1 LINES
;


LOAD DATA LOCAL
INFILE 'Contains.csv' 
REPLACE
INTO TABLE Contains
FIELDS TERMINATED BY ','	
IGNORE 1 LINES
;
