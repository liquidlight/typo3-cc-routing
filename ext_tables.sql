#
# Table structure for table 'tx_ccrouting_pathsegment'
#
CREATE TABLE tx_ccrouting_pathsegment (

	data_uid int(11) DEFAULT '0' NOT NULL,
	pathsegment varchar(255) DEFAULT '' NOT NULL,
	tablename varchar(255) DEFAULT '' NOT NULL,

	KEY enable (deleted,starttime,endtime),
	KEY data (tablename,data_uid),
	KEY pathsegment (pathsegment),

);
