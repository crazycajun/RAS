drop procedure if exists addColumn;

delimiter //

-- This procedure will add the column to the specified database table
-- if it is not already defined.
create procedure addColumn (
	 in dbName varchar(128)
	,in tableName varchar(256)
	,in columnName varchar(256)
	,in definition tinytext
)
begin
	if NOT Exists(
		select
			column_name
		from
			information_schema.COLUMNS
		where
			column_name = columnName
			and table_name = tableName
			and table_schema = dbName
	) then

		set @ddl = Concat('alter table ', dbName, '.' 
			, tableName, ' add column ', columnName, ' ', definition);
		prepare stmt from @ddl;
		execute stmt;
		
	end if;
end //

delimiter ;