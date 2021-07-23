<?php
class db {

    protected $connection;
	protected $query;
    protected $dbname;

	public function __construct($dbhost, $dbuser, $dbpass, $dbname, $dbport = 3307) {
		$this->connection = @new PDO("mysql:dbname=" . $dbname . ";host=" . $dbhost . ";port=". $dbport, $dbuser, $dbpass);
        $this->connection->exec("SET CHARACTER SET utf8mb4");
		$this->dbname = $dbname;
        // if ($this->connection->connect_error) {
		// 	$this->error('Failed to connect to MariaDB - ' . $this->connection->connect_error);
		// }
		// $this->connection->set_charset($charset);
	}

    public function query($query) {
		$this->query = $this->connection->query($query);
		return $this;
    }

    public function fetchObj(){
        return $this->query->fetchAll(PDO::FETCH_OBJ);
    }

	public function fetchArray() {
	    return $this->query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function close() {
		return $this->connection->close();
	}

	public function affectedRows() {
		return $this->query->rowCount();
	}

    public function lastInsertID() {
    	return $this->connection->lastInsertId();
    }

    public function getAutoIncrement($table){
        $data = $this->query("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '".$this->dbname."' AND TABLE_NAME = '".$table."'")->fetchObj();
        return $data[0]->AUTO_INCREMENT;
    } 
}
?>