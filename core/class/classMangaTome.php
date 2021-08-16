<?php
class MangaTome{

    public string $number = '';
    public string $image = '';
    public string $idManga = '';

    /**
     * construct
     * @param int $id 
     */
    public function __construct(public Database $PDO, public int $idTome = 0)
    {
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
    
        $data = $data->fetchObj();
        $this->number = $data[0]->number;
        $this->image = $data[0]->image;
        $this->idManga = $data[0]->id_manga;
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
        $data = $this->PDO->query($sql)->fetchObj();
        $this->number = $data[0]->number;
        $this->image = $data[0]->image;
        $this->idManga = $data[0]->id_manga;
        return $this;
    }
}

