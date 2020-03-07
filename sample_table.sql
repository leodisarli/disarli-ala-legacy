CREATE TABLE mydb.dbo.[sample] (
	id nchar(36) NOT NULL,
	field int NOT NULL,
	created datetime NOT NULL,
	modified datetime NOT NULL,
	deleted datetime
);
CREATE INDEX sample_created_index ON mydb.dbo.[sample] (created);
CREATE INDEX sample_deleted_index ON mydb.dbo.[sample] (deleted);
CREATE INDEX sample_id_index ON mydb.dbo.[sample] (id);
CREATE INDEX sample_modified_index ON mydb.dbo.[sample] (modified);
