<?php

// PREVOIR CAS OU IL N'Y A PAS DE USER ?? FAIRE QUE NOM URL EST UN USER ET TESTER SI EXISTE :)
$userObj = new User(6);
$userPercentCollection = 85/($userObj->nbHave + $userObj->nbHavent + $userObj->nbWish);

?>

<!DOCTYPE html>
</html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?= RACINE_SITE ?>core/css/style1_0_0.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container-fluid overflow-hidden">
        <div class="row vh-100 overflow-auto">
            <header class="col-12 col-sm-3 col-xl-2 px-sm-2 px-0 bg-dark d-flex sticky-top pb-2 pb-sm-0">
                <div class="d-flex flex-sm-column flex-row flex-grow-1 align-items-center align-items-sm-start px-1 pt-2 text-white">
                    <span  class="pb-5 d-none d-sm-inline">
                        <span class="fs-4 text-roseh">Manga</span>
                    </span>
                    <nav class="nav nav-pills row mb-sm-auto mb-0">
                        <div class="col-10 col-md-12 d-none d-sm-inline">
                            <div class="progress">
                                <div class="progress-bar bg-success text-pourpre" role="progressbar" style="width: <?= floor($userPercentCollection*$userObj->nbHave) + 5; ?>%" aria-valuenow="<?= floor($userPercentCollection*$userObj->nbHave) + 5; ?>" aria-valuemin="0" aria-valuemax="100"><?= $userObj->nbHave ?></div>
                                <div class="progress-bar bg-warning text-pourpre" role="progressbar" style="width: <?= floor($userPercentCollection*$userObj->nbWish) + 5; ?>%" aria-valuenow="<?= floor($userPercentCollection*$userObj->nbWish) + 5; ?>" aria-valuemin="0" aria-valuemax="100"><?= $userObj->nbWish ?></div>
                                <div class="progress-bar bg-secondary text-pourpre" role="progressbar" style="width: <?= floor($userPercentCollection*$userObj->nbHavent) + 5; ?>%" aria-valuenow="<?= floor($userPercentCollection*$userObj->nbHavent) + 5; ?>" aria-valuemin="0" aria-valuemax="100"><?= $userObj->nbHavent ?></div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-3">
                            <a href="#" class="nav-link px-sm-0 px-2 text-white">
                                <i class="fs-5 bi-house">(<?= $userObj->nbHave + $userObj->nbHavent + $userObj->nbWish ?>)</i><span class="ms-1 d-none d-sm-inline text-decoration-underline">Total</span>
                            </a>
                        </div>
                        <div class="col-sm-12 col-3">
                            <a href="#" class="nav-link px-sm-0 px-2 text-success">
                                <i class="fs-5 bi-house">(<?= $userObj->nbHave ?>)</i><span class="ms-1 d-none d-sm-inline text-decoration-underline">Possédés</span>
                            </a>
                        </div>
                        <div class="col-sm-12 col-3">
                            <a href="#" class="nav-link px-sm-0 px-2 text-warning">
                                <i class="fs-5 bi-house">(<?= $userObj->nbWish ?>)</i><span class="ms-1 d-none d-sm-inline text-decoration-underline">Souhaités</span>
                            </a>
                        </div>
                        <div class="col-sm-12 col-3">
                            <a href="#" class="nav-link px-sm-0 px-2 text-secondary">
                                <i class="fs-5 bi-house">(<?= $userObj->nbHavent ?>)</i><span class="ms-1 d-none d-sm-inline text-decoration-underline">Manquants</span>
                            </a>
                        </div>
                        <div class="col-12 mt-5 d-none d-sm-inline">
                            <h5>Genre</h5>
                            <?php foreach((new Reference('genre'))->getTableList() as $elem): ?>
                                <span class="badge bg-success cursor-pointer user-select-none"><?= $elem->label ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="col-12 mt-5 d-none d-sm-inline">
                            <h5>Catégorie</h5>
                            <?php foreach((new Reference('categorie'))->getTableList() as $elem): ?>
                                <span class="badge bg-info cursor-pointer user-select-none"><?= $elem->label ?></span>
                            <?php endforeach; ?>
                        </div>
                    </nav>
                    <div class="dropdown py-sm-4 mt-sm-auto ms-auto ms-sm-0 flex-shrink-1">
                        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://avatars.githubusercontent.com/u/49068145?s=400&u=afa4e28bd124af2977d352c2992297f5b21792d1&v=4" alt="hugenerd" width="32" height="32" class="rounded-circle">
                            <span class="d-none d-sm-inline mx-1">Kanzaki Urumi</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                            <li><a class="dropdown-item" href="#">Nouvelle Série</a></li>
                            <li><a class="dropdown-item" href="#">Profil</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Déconnexion</a></li>
                        </ul>
                    </div>
                </div>
            </header>
            <div class="col d-flex flex-column h-100">
                <main class="row overflow-auto pt-2">