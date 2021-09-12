<?php
class User{

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
                ->getLibraryByState()
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

    public function getLibraryByState(array $idGenre = [], array $idCategorie = [], null|int $idTomeState = null):User|false
    {

        $sql = "SELECT * FROM `user_manga` AS `um` 
        INNER JOIN `manga` AS `m` ON `m`.`id_manga` = `um`.`id_manga` ";

        if(count($idGenre) > 0)
            $sql .= "AND `m`.`id_genre` IN(".implode(',',array_map('intval',$idGenre)).") ";

        $sql .= "INNER JOIN `manga_tome` AS `mt` ON `mt`.`id_manga` = `m`.`id_manga` 
        LEFT JOIN `user_tome` AS `ut` ON `mt`.`id_tome` = `ut`.`id_tome` 
        INNER JOIN `manga_categorie` AS `mc` ON `mc`.`id_manga` = `m`.`id_manga` ";

        if(count($idCategorie) > 0)
            $sql .= "AND `mc`.`id_categorie` IN(".implode(',',array_map('intval',$idCategorie)).") ";

        $sql .= "WHERE `um`.`id_user` = ".intval($this->idMembre)." ";

        if($idTomeState > 0)
            $sql .= "AND `ut`.`id_tome_status` = ".intval($idTomeState)." ";

        if($idTomeState === 0)
            $sql .= "AND `ut`.`id_tome_status` IS NULL ";

        $sql .= "GROUP BY `mt`.`id_tome` 
        ORDER BY `m`.`name` ASC, `mt`.`number` ASC ;";

        // Reset manga list
        $this->manga = [];
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

    /**
     * changeStateTome
     * @param int $idTome Id tome must be edit/delete/add
     * @param string $state State : add/wish/delete
     */
    public function changeStateTome(int $idTome, string $state)
    {
        $sql = "SELECT * FROM `manga_tome` AS `mt` 
                INNER JOIN `user_manga` AS `um` ON `um`.`id_manga` = `mt`.`id_manga` 
                WHERE `mt`.`id_tome` = ".intval($idTome)." AND `um`.`id_user` = ".intval($this->idMembre)." ";
        $nbFound = $this->PDO->query($sql)->affectedRows();

        if($nbFound != 1)
            return $nbFound;

        switch($state):
            case 'delete': 
                $sql = "DELETE `ut`.* FROM `user_tome` AS `ut` 
                        INNER JOIN `user_manga` AS `um` ON `um`.`id_user_manga` = `ut`.`id_user_manga` 
                        WHERE `ut`.`id_tome` = ".intval($idTome)." AND `um`.`id_user` = ".intval($this->idMembre)." ";
                break;
            case 'wish':
            case 'add':
                $idStatutTome = ($state == 'wish')? 2 : 1;
                $sql = "INSERT INTO `user_tome` (id_user_manga, id_tome, id_tome_status) 
                        SELECT `um`.`id_user_manga`, ".intval($idTome).", ".intval($idStatutTome)." 
                        FROM `manga_tome` AS `mt` 
                        INNER JOIN `user_manga` AS `um` ON `um`.`id_manga` = `mt`.`id_manga` 
                        WHERE `um`.`id_user` = ".intval($this->idMembre)." AND `mt`.`id_tome` = ".intval($idTome)." 
                        ON DUPLICATE KEY UPDATE `id_tome_status` = ".intval($idStatutTome)." ";
                break;
            default: exit;
        endswitch;
        $this->PDO->query($sql);
    }

    public function getListMangaHavent()
    {
        $sql = "SELECT `m`.`id_manga` FROM `manga` AS `m` 
        LEFT JOIN `user_manga` AS `um` ON `um`.`id_manga` = `m`.`id_manga` AND `um`.`id_user` = ".$this->idMembre." 
        WHERE `um`.`id_user_manga` IS NULL ORDER BY `m`.`name`";
        return $this->PDO->query($sql)->fetchObj();
    }

    /**
     * addMangaLibrary
     * @param int $idManga id of the manga
     * @param bool $Action true for add // false for remove
     * @return bool true if done, else false
     */
    public function addMangaLibrary(int $idManga, bool $Action):bool
    {
        // existing state
        $existOnList = array_key_exists($idManga, $this->manga);

        if($existOnList === $Action)
            return false;

        try{
            // test if manga exist
            $infoManga = new Manga($idManga);
            if($Action){
                $sql = "INSERT INTO `user_manga` (id_user_manga, id_user, id_manga) VALUES (null, ".$this->idMembre.", ".$idManga.") ";
            }else{
                $sql = "DELETE FROM `user_manga` WHERE `id_manga` = ".$idManga." AND `id_user` = ".$this->idMembre." ";
            }   
            $this->PDO->query($sql);
            return true;
        }catch(Exception $e){
            return false;
        }
    }
}