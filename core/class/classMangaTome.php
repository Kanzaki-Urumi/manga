<?php
class MangaTome{

    public int|null $number = null;
    public string $image = '';
    public int|null $idManga = null;
    protected Database $PDO;

    /**
     * construct
     * @param int $id 
     */
    public function __construct(public int $idTome = 0)
    {
        $this->PDO = new Database();

        if($idTome > 0)
            return $this->readIdTome();

        return $this;
    }

    /**
     * get all database info Tome
     *
     * @return MangaTome|false
     */
    public function readIdTome(): MangaTome|false
    {
        $sql = "SELECT * FROM `manga_tome` WHERE `id_tome` = ".intval($this->idTome)." ";
        $data = $this->PDO->query($sql);
        if($data->affectedRows() != 1)
            return false;
    
        $data = $data->firstRow();
        $this->number = $data->number;
        $this->image = $data->image;
        $this->idManga = $data->id_manga;
        return $this;
    }

    /**
     * Add or Update Tome
     *
     * @return MangaTome|false
     */
    public function saveTome(): MangaTome|false
    {
        $sql = "INSERT INTO `manga_tome` (id_manga, number, image) 
        VALUES (".intval($this->idManga).", ".intval($this->number).", '".clean_for_bdd($this->image)."')
        ON DUPLICATE KEY UPDATE id_manga = VALUES(id_manga), number = VALUES(number), image = VALUES(image)";

        // If cant Update, cant Insert or nothing to update
        if($this->PDO->query($sql)->affectedRows() == 0)
            return false;

        $sql = "SELECT * FROM `manga_tome` WHERE id_manga = ".intval($this->idManga)." AND number = ".intval($this->number)." ";
        $data = $this->PDO->query($sql)->firstRow();
        $this->number = $data->number;
        $this->image = $data->image;
        $this->idManga = $data->id_manga;
        return $this;
    }
}

