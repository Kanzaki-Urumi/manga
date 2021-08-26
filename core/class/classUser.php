<?php
class User{

    public string $test = '';
    public string $pseudo = '';
    public int|null $idType = null;
    public array $manga = [];
    public int $nbHave = 0;
    public int $nbWish = 0;
    public int $nbHavent = 0;
    protected Database $PDO;

    /**
     * construct
     * @param int $idMembre 
     */
    public function __construct(public int|null $idMembre = null)
    {
        $this->PDO = new Database();

        if($idMembre > 0){
            return $this
                ->getLibraryByState(1,[1],null)
                ->getMangaHave()
                ->getMangaWish()
                ->getMangaHavent();
        }
        return $this;        
    }

    /**
     * readLibrary
     * @return User|false
     */
    public function readLibrary():User|false
    {
        // Left join, case where user havent any tome for Manga name
        $sql = "SELECT `um`.`id_manga`, `ut`.`id_tome`, `ut`.`id_tome_status` 
        FROM `user_manga` AS `um` 
        LEFT JOIN `user_tome` AS `ut` ON `um`.`id_user_manga` = `ut`.`id_user_manga` 
        INNER JOIN `manga` AS `m` ON `m`.`id_manga` = `um`.`id_manga` 
        INNER JOIN `manga_tome` AS `mt` ON `mt`.`id_tome` = `ut`.`id_tome`
        WHERE `um`.`id_user` = ".intval($this->idMembre)." 
        ORDER BY `m`.`name` ASC, `mt`.`number` ASC";

        foreach($this->PDO->query($sql)->fetchObj() as $elem){
            $this->manga[(int) $elem->id_manga][(int) $elem->id_tome] = (int) $elem->id_tome_status;
        }
        return $this;
    }

    public function getLibraryByState(null|array $idGenre = null, null|array $idCategorie = null, null|int $idTomeState = null):User|false
    {
        $sql = "SELECT `um`.`id_manga`, `ut`.`id_tome`, `ut`.`id_tome_status` 
        FROM `user_manga` AS `um` ";

        if($idTomeState > 0){
           $sql .= "INNER JOIN `user_tome` AS `ut` ON `um`.`id_user_manga` = `ut`.`id_user_manga` AND  `ut`.`id_tome_status` = ".$idTomeState." ";
        }else{
            $sql .= "LEFT JOIN `user_tome` AS `ut` ON `um`.`id_user_manga` = `ut`.`id_user_manga` ";
        }

        $sql .= "INNER JOIN `manga` AS `m` ON `m`.`id_manga` = `um`.`id_manga` ";
        if($idGenre != null){
            $sql .= "AND `m`.`id_genre` IN(".implode(',',$idGenre).") ";
        }

        if($idCategorie != null){
            $sql .= "LEFT JOIN `manga_categorie` AS `mc` ON `mc`.`id_manga` = `m`.`id_manga` AND `mc`.`id_categorie` IN(".implode(',',$idCategorie).") ";
        }

        
        $sql .= "INNER JOIN `manga_tome` AS `mt` ON `mt`.`id_tome` = `ut`.`id_tome`
        WHERE `um`.`id_user` = ".intval($this->idMembre)." 
        ORDER BY `m`.`name` ASC, `mt`.`number` ASC";

        foreach($this->PDO->query($sql)->fetchObj() as $elem){
            $this->manga[(int) $elem->id_manga][(int) $elem->id_tome] = (int) $elem->id_tome_status;
        }
        $this->test = $sql;
        return $this;
    }

    public function getLibraryFiltered(int|false $idGenre = false, int|false $idCategorie = false, int|false $idPossession = false)
    {
        // Left join, case where user havent any tome for Manga name
        $sql = "SELECT `um`.`id_manga`, `ut`.`id_tome`, `ut`.`id_tome_status` 
        FROM `user_manga` AS `um` 
        LEFT JOIN `user_tome` AS `ut` ON `um`.`id_user_manga` = `ut`.`id_user_manga` 
        INNER JOIN `manga` AS `m` ON `m`.`id_manga` = `um`.`id_manga` 
        INNER JOIN `manga_tome` AS `mt` ON `mt`.`id_tome` = `ut`.`id_tome`
        WHERE `um`.`id_user` = ".intval($this->idMembre)." AND `m`.`id_genre` = ".$id." 
        ORDER BY `m`.`name` ASC, `mt`.`number` ASC";

        foreach($this->PDO->query($sql)->fetchObj() as $elem){
            $this->manga[(int) $elem->id_manga][(int) $elem->id_tome] = (int) $elem->id_tome_status;
        }
        return $this;
    }


    /**
     * getListGenre
     * @return array
     */
    public function getListGenre():array
    {
        $sql = "SELECT `rg`.`id_genre`, `rg`.`label` 
        FROM `user_manga` AS `um` 
        INNER JOIN `manga` AS `m` ON `m`.`id_manga` = `um`.`id_manga` 
        INNER JOIN `ref_genre` AS `rg` ON `m`.`id_genre` = `rg`.`id_genre` ";
        return $this->PDO->query($sql)->fetchObj();
    }

    /**
     * getListCat
     * @return array
     */
    public function getListCat():array
    {
        $sql = "SELECT `rc`.`id_categorie`, `rc`.`label` 
        FROM `user_manga` AS `um` 
        INNER JOIN `manga_categorie` AS `mc` ON `mc`.`id_manga` = `um`.`id_manga` 
        INNER JOIN `ref_categorie` AS `rc` ON `mc`.`id_categorie` = `rc`.`id_categorie` ";
        return $this->PDO->query($sql)->fetchObj();
    }

    /**
     * getMangaHave
     * @return User
     */
    public function getMangaHave():User
    {
        $sql = "SELECT * FROM `user_tome` AS `ut` 
        INNER JOIN `user_manga` AS `um` ON `um`.`id_user_manga` = `ut`.`id_user_manga` 
        WHERE `id_tome_status` = 1 AND `um`.`id_user` = ".$this->idMembre." ";
        $this->nbHave = $this->PDO->query($sql)->affectedRows();

        return $this;
    }

    /**
     * getMangaWish
     * @return User
     */
    public function getMangaWish():User
    {
        $sql = "SELECT * FROM `user_tome` AS `ut` 
        INNER JOIN `user_manga` AS `um` ON `um`.`id_user_manga` = `ut`.`id_user_manga` 
        WHERE `id_tome_status` = 2 AND `um`.`id_user` = ".$this->idMembre." ";
        $this->nbWish = $this->PDO->query($sql)->affectedRows();

        return $this;
    }

    /**
     * getMangaHavent : (Total possible tome) - (Have + Wish)
     * @return User
     */
    public function getMangaHavent():User
    {
        $sql = "SELECT * FROM `user_manga` AS `um` 
        INNER JOIN `manga_tome` AS `mt` ON `mt`.`id_manga` = `um`.`id_manga` 
        LEFT JOIN `user_tome` AS `ut` ON `ut`.`id_tome` = `mt`.`id_tome`
        WHERE `um`.`id_user` = ".$this->idMembre." AND `ut`.`id_user_tome` IS NULL";
        $this->nbHavent = $this->PDO->query($sql)->affectedRows();

        return $this; 
    }


}