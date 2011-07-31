/*
 *		team members:	Brian Chiasson, Dan Buhrman
 *		date:				2011-07-31 
 *		
 * 	description:	This script creates the user table for the ras application
 *		system.
 *		
 */

create table if NOT exists user_accounts(
	 id int not null primary key auto_increment
	,name varchar(256) not null
	,password varchar(40) not null
	,email varchar(512) not null
	,created_on datetime not null
	,activated_on datetime null
	,activation_token varchar(48) not null
	,deactivated_on datetime null
);
