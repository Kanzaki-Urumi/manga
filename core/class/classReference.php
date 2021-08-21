<?php
class Reference{
    
    protected Database $PDO;

    /**
     * construct
     * @param int $id 
     */
    public function __construct(public string $table)
    {
        $this->PDO = new Database();
        return $this;
    }

    /**
     * readRef
     * @param int $idRef 
     * @return string
     */
    public function readRef(int $idRef):string
    {
        $sql= "SELECT `label` FROM `ref_".$this->table."` WHERE `id_".$this->table."` = ".intval($id)." ";
        return $this->PDO->query($sql)->firstRow()->label;
    }

    /**
     * getTableList
     *
     * @return array|object
     */
    public function getTableList():array|object
    {
        $sql= "SELECT * FROM `ref_".$this->table."` ";
        return $this->PDO->query($sql)->fetchObj();
    }
}