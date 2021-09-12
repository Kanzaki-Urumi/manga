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
            <header class="col-12 col-sm-4 col-xl-2 px-sm-2 px-0 bg-dark d-flex sticky-top pb-2 pb-sm-0">
                <div class="d-flex flex-sm-column flex-row flex-grow-1 align-items-center align-items-sm-start px-1 pt-2 text-white">
                    <span  class="pb-3 d-none d-sm-inline">
                        <span class="fs-4 text-primary">Manga</span>
                    </span>
                    <nav class="nav nav-pills row mb-sm-auto mb-0">
                        <div class="col-sm-12 col-3">
                            <a href="userMangaListe.php" class="nav-link px-sm-0 px-2 text-roseh">
                                <span class="fs-5 ms-1 d-none d-sm-inline text-decoration-underline">Tous mes Mangas</span>
                            </a>
                        </div>
                        <div class="col-sm-12 col-3">
                            <a href="addManga.php" class="nav-link px-sm-0 px-2 text-roseh">
                                <span class="fs-5 ms-1 d-none d-sm-inline text-decoration-underline">Ajouter un Manga</span>
                            </a>
                        </div>
                        <div class="col-sm-12 col-3">
                            <a href="index.php?type=all" class="nav-link px-sm-0 px-2 text-roseh">
                                <span class="fs-5 ms-1 d-none d-sm-inline text-decoration-underline">Tous mes Tomes (<?= $userObj->nbHave + $userObj->nbHavent + $userObj->nbWish ?>)</span>
                            </a>
                        </div>
                        <div class="col-sm-12 col-3">
                            <a href="index.php?type=have" class="nav-link px-sm-0 px-2 text-roseh">
                                <span class="fs-5 ms-1 d-none d-sm-inline text-decoration-underline">Tomes Possédés (<?= $userObj->nbHave ?>)</span>
                            </a>
                        </div> 
                        <div class="col-sm-12 col-3">
                            <a href="index.php?type=wish" class="nav-link px-sm-0 px-2 text-roseh">
                                <span class="fs-5 ms-1 d-none d-sm-inline text-decoration-underline">Tomes Souhaités (<?= $userObj->nbWish ?>)</span>
                            </a>
                        </div>
                        <div class="col-sm-12 col-3">
                            <a href="index.php?type=havent" class="nav-link px-sm-0 px-2 text-roseh">
                                <span class="fs-5 ms-1 d-none d-inline text-decoration-underline">Tomes Manquants (<?= $userObj->nbHavent ?>)</span>
                            </a>
                        </div>
                    </nav>
                </div>
            </header>
            <div class="col d-flex flex-column h-100">
                <main class="row overflow-auto pt-2 pb-5">