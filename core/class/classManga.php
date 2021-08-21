<?php
class Manga{

    public string $name = '';
    public string $mangaka = '';
    public int|null $idType = null;
    public array $tomes = [];
    protected Database $PDO;

    /**
     * construct
     * @param int $id 
     */
    public function __construct(public int $idManga = 0)
    {
        $this->PDO = new Database();

        if($idManga > 0)
            return $this->readIdManga();

        return $this;        
    }

    /**
     * get all database info Tome
     *
     * @param int $id
     * @return Manga|false
     */
    public function readIdManga(int $id = null):Manga|false
    {
        $sql = "SELECT * FROM `manga` WHERE `id_manga` = ".intval($id??$this->idManga)." ";
        $data = $this->PDO->query($sql);
        if($data->affectedRows() != 1)
            return false;
    
        $data = $data->firstRow();
        $this->name = $data->name;
        $this->mangaka = $data->mangaka;
        $this->idType = $data->id_type;
        $this->idManga = $data->id_manga; // Case where we pass with $id
        return $this->getAllTomes();
    }

    /**
     * get all manga Tome
     * @return Manga|false
     */
    public function getAllTomes():Manga|false
    {
        $sql = "SELECT * FROM `manga_tome` WHERE `id_manga` = ".intval($this->idManga)." ";
        $data = $this->PDO->query($sql)->fetchObj();
        foreach($data as $elem){
            $this->tomes[(int) $elem->id_tome] = new MangaTome($elem->id_tome);
        }
        return $this;
    }
}