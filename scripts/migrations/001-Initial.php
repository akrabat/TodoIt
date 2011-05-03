<?php 

class Initial extends Akrabat_Db_Schema_AbstractChange 
{
    function up()
    {
        $sql[] = "
            CREATE TABLE IF NOT EXISTS tasks (
              id int NOT NULL AUTO_INCREMENT,
              title varchar(200) NOT NULL,
              notes text,
              due_date datetime,
              created_by int,
              date_completed datetime,
              date_created datetime NOT NULL,
              PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $sql[] = "INSERT INTO tasks (title, due_date, date_created, created_by) VALUES ('Task 1', '2011-05-30', NOW(), 1);";
        $sql[] = "INSERT INTO tasks (title, due_date, date_created, created_by) VALUES ('Task 2', '2011-05-10', NOW(), 2);";
        $sql[] = "INSERT INTO tasks (title, due_date, date_created, created_by) VALUES ('Task 3', '2011-06-05', NOW(), 2);";
        
        $this->_runSQL($sql);
    }
    
    function down()
    {
        $sql[] = "DROP TABLE IF EXISTS tasks;";
        $this->_runSQL($sql);
    }
    
    protected function _runSQL($sql)
    {
        foreach ($sql as $script) {
            $this->_db->query($script);
        }
    }
}
