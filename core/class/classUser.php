<?php
class User{

    public string $pseudo = '';
    public int|null $idType = null;
    public array $manga = [];
    protected Database $PDO;

    /**
     * construct
     * @param int $idMembre 
     */
    public function __construct(public int|null $idMembre = null)
    {
        $this->PDO = new Database();

        if($idMembre > 0)
            return $this->readLibrary();

        return $this;        
    }

    /**
     * readLibrary
     *
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
}