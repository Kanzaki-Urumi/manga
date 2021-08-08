<?php
class manga{


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
     * readMangaInfo
     * @param int $id 
     * @return void
     */
    public function readMangaInfo(): object
    {
        
    }



}