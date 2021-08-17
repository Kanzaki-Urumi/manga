<?php
class User{

    public string $pseudo = '';
    public int|null $idType = null;
    public array $manga = [];

    /**
     * construct
     * @param Database $PDO 
     * @param int $idMembre 
     */
    public function __construct(public Database $PDO, public int|null $idMembre = null)
    {
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
        $sql = "SELECT `um`.`id_manga`, `um`.`id_tome`, `um`.`id_tome_status` 
        FROM `user_manga` AS `um` 
        LEFT JOIN `user_tome` AS `ut` ON `um`.`id_user_manga` = `ut`.`id_user_manga` 
        INNER JOIN `manga` AS `m` ON `id_manga` = `um`.`id_manga` 
        INNER JOIN `manga_tome` AS `mt` ON `mt`.`id_tome` = `um`.`id_tome`
        WHERE `um`.`id_user_tome` = ".intval($this->idMembre)." 
        ORDER BY `m`.`name` ASC, `mt`.`number` ASC";

        foreach($this->PDO->query($sql)->fetchObj() as $elem){
            $this->manga[$elem->id_manga] = [$elem->id_tome => $id_tome_status];
        }

        return $this;
    }









}