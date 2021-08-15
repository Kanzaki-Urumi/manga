<?php
class Manga{

    public string $name = '';
    public string $mangaka = '';
    public array $tomes = [];

    /**
     * construct
     * @param int $id 
     */
    public function __construct(public int $id = 0)
    {
        
    }

    /**
     * get all manga Tome
     * @param int $id_manga
     * @return object manga
     */
    public function getAllTomes(){
        
    }

    /**
     * readMangaInfo
     * @param int $id 
     * @return void
     */
    public function readMangaInfo(): object
    {
        
    }



}