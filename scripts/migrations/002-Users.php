<?php 

class Users extends Akrabat_Db_Schema_AbstractChange 
{
    function up()
    {
        $sql[] = "
            CREATE TABLE IF NOT EXISTS users (
              id int NOT NULL AUTO_INCREMENT,
              username varchar(50) NOT NULL,
              password varchar(50) NOT NULL,
              salt varchar(50) NOT NULL,
              role varchar(50) NOT NULL,
              date_created datetime NOT NULL,

              PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $sql[] = "INSERT INTO users (username, password, salt, role, date_created) 
            VALUES ('admin', SHA1('passwordce8d96d579d389e783f95b3772785783ea1a9854'),
            'ce8d96d579d389e783f95b3772785783ea1a9854', 'administrator', NOW());";
        $sql[] = "INSERT INTO users (username, password, salt, role, date_created) 
            VALUES ('rob', SHA1('passwordce8d96d579d389e783f95b3772785783ea1a9854'),
            'ce8d96d579d389e783f95b3772785783ea1a9854', 'user', NOW());";
        
        $this->_runSQL($sql);
    }
    
    function down()
    {
        $sql[] = "DROP TABLE IF EXISTS users;";
        $this->_runSQL($sql);
    }
    
    protected function _runSQL($sql)
    {
        foreach ($sql as $script) {
            $this->_db->query($script);
        }
    }
}
