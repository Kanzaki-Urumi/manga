<?php
require_once('../core/define.php');
// check if user connected !!!

$id = $_GET['id'];
$state = $_GET['state'];

// All pour tout, sinon en fonction de state !!
$MangaInfo = (new Manga($id))->getAllTomes();
$userObj = new User(6);

// return data with depend type
$ajaxTomeStateSearch = match($state){
    'have' => 'add',
    'havent' => 'delete',
    'wish' => 'wish',
    default => 'everything',
};

$arrayType = ['add', 'wish', 'delete'];

$jsonData = [];
foreach($MangaInfo->tomes as $idTome => $elem){
    $idStateTome = $userObj->manga[$id][$idTome]??0;
    switch($idStateTome):
        case 0: $current = 'delete'; break;
        case 1: $current = 'add'; break;
        case 2: $current = 'wish'; break;
    endswitch;
    if(in_array($ajaxTomeStateSearch, $arrayType) && $current == $ajaxTomeStateSearch){
        $jsonData[] = [
            'tome' => $idTome,
            'img' => $elem->image,
            'border' => 'border-'.getClassBorderColorStateTome($idStateTome),
            'state' => getClassStateTome($idStateTome),
            'current' => $current,
        ];
    }elseif(!in_array($ajaxTomeStateSearch, $arrayType)){
        $jsonData[] = [
            'tome' => $idTome,
            'img' => $elem->image,
            'border' => 'border-'.getClassBorderColorStateTome($idStateTome),
            'state' => getClassStateTome($idStateTome),
            'current' => $current,
        ];
    }
}

echo json_encode($jsonData);