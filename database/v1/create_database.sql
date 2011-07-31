/*
 *		team members:	Brian Chiasson, Dan Buhrman
 *		date:				2011-07-27 
 *		
 * 	description:	This script contains the setup data for the database needed
 * 	to run the RAS web application for database version 1. This script is designed
 *		to be re-runnable without side effects. The goal is to create the database
 *		and the corresponding users that are required to get the application running.
 *		Note that you must have the appropriate MySQL GRANT permissions to execute 
 *		this script.	
 *		
 */


create database if NOT exists ras;

-- Remove if the user exists to prevent key violations.
delete from 
	mysql.user
where
	User = 'rasWebUsers'
	and Host = 'localhost';

-- Create the user with the specified password.
insert into mysql.user( 
	 Host
	,User
	,Password
)
values(
	 'localhost'
	,'rasWebUsers'
	,'*C3D36B74FFD84DBD34A2A738742AE451B9A40445'
);

grant 
	 SELECT
	,INSERT
	,UPDATE
	,CREATE TEMPORARY TABLES
	,EXECUTE
	,LOCK TABLES
	,TRIGGER 
	on 
		ras.* 
	to 
		'rasWebUsers'@'localhost'
