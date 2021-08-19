<?php
class Manga{

    public string $name = '';
    public string $mangaka = '';
    public int|null $idType = null;
    public array $tomes = [];

    /**
     * construct
     * @param Database $PDO 
     * @param int $id 
     */
    public function __construct(public Database $PDO, public int $idManga = 0)
    {
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
    
        $data = $data->fetchObj();
        $this->name = $data[0]->name;
        $this->mangaka = $data[0]->mangaka;
        $this->idType = $data[0]->id_type;
        $this->idManga = $data[0]->id_manga; // Case where we pass with $id
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
            $this->tomes[(int) $elem->id_tome] = new MangaTome($this->PDO,$elem->id_tome);
        }
        return $this;
    }
}