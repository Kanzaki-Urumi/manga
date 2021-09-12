<?php
class Manga{

    public string $name = '';
    public string $mangaka = '';
    public int|null $idGenre = null;
    public array $tomes = [];
    public array $categories = [];
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
     * @return Manga|Exception
     */
    public function readIdManga(int $id = null):Manga|Exception
    {
        $sql = "SELECT * FROM `manga` WHERE `id_manga` = ".intval($id??$this->idManga)." ";
        $data = $this->PDO->query($sql);
        if($data->affectedRows() != 1)
            return throw new Exception('error');
    
        $data = $data->firstRow();
        $this->name = $data->name;
        $this->mangaka = $data->mangaka;
        $this->idGenre = $data->id_genre;
        $this->idManga = $data->id_manga; // Case where we pass with $id
        // return $this->getAllTomes()->getAllCategories(); // Remove getAllTomes for better load
        return $this->getAllCategories();
    }

    /**
     * get all manga Tome
     * @return Manga|false
     */
    public function getAllTomes():Manga|false
    {
        if($this->idManga < 1)
            return false;

        $sql = "SELECT * FROM `manga_tome` WHERE `id_manga` = ".intval($this->idManga)." ";
        $data = $this->PDO->query($sql)->fetchObj();
        foreach($data as $elem){
            $this->tomes[(int) $elem->id_tome] = new MangaTome($elem->id_tome);
        }
        return $this;
    }

    /**
     * getAllCategories
     * @return Manga|false
     */
    public function getAllCategories():Manga|false
    {
        if($this->idManga < 1)
            return false;

        $sql = "SELECT `rc`.* 
        FROM `manga_categorie` AS `mc` 
        INNER JOIN `ref_categorie` AS `rc` ON `rc`.`id_categorie` = `mc`.`id_categorie` 
        WHERE `mc`.`id_manga` = ".$this->idManga." ";
        $data = $this->PDO->query($sql)->fetchObj();
        foreach($data as $elem){
            $this->categories[(int) $elem->id_categorie] = $elem->label;
        }
        return $this;
    }
}