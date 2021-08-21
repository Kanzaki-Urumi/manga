<?php
class Database {

    protected $connection;
	protected $query;
	public $data;

	public function __construct(
		protected string $dbhost = "localhost",
		protected string $dbuser = "root",
		protected string $dbpass = "",
		protected string $dbname = "manga",
		protected int $dbport = 3307) {
		$this->connection = @new PDO("mysql:dbname=" . $this->dbname . ";host=" . $this->dbhost . ";port=". $this->dbport, $this->dbuser, $this->dbpass);
        $this->connection->exec("SET CHARACTER SET utf8mb4");
		$this->dbname = $dbname;
	}

	/**
	 * query
	 *
	 * @param string $query 
	 * @return Database
	 */
    public function query(string $query):Database
	{
		$this->query = $this->connection->query($query);
		return $this;
    }

	/**
	 * fetchObj
	 * @return array
	 */
    public function fetchObj():array
	{
		return $this->query->fetchAll(PDO::FETCH_OBJ);
    }

	/**
	 * firstRow
	 * @return object
	 */
	public function firstRow():object
	{
		return ($this->fetchObj())[0];
	}

	/**
	 * close
	 * @return void
	 */
	public function close():void
	{
		$this->connection->close();
	}

	/**
	 * affectedRows
	 *
	 * @return int
	 */
	public function affectedRows():int
	{
		return (int) $this->query->rowCount();
	}

	/**
	 * lastInsertID
	 *
	 * @return int
	 */
    public function lastInsertID():int
	{
    	return (int) $this->connection->lastInsertId();
    }

	/**
	 * getAutoIncrement
	 *
	 * @param string $table 
	 * @return int
	 */
    public function getAutoIncrement(string $table)
	{
        $data = $this->query("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '".$this->dbname."' AND TABLE_NAME = '".$table."'")->firstRow()->AUTO_INCREMENT;
        return (int) $data;
    } 
}
?>