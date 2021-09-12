<?php

require_once('core/define.php');


include_once('core/header.php');

$mangaListHavent = $userObj->getListMangaHavent();


?>
<div class="col-12 collection-gallery mx-auto">
    <div class="row justify-content-around">
        <?php foreach($mangaListHavent as $id => $elem):
            $infoManga = new Manga( (int) $elem->id_manga );
        ?>
            <div class="px-2 col-12 col-lg-6 col-xl-4 blocMangaAdd">
                <div class="row justify-content-between border border-info p-3 m-2">
                    <div class="col-4 p-0">
                        <img alt="<?= $infoManga->name ?> Tome 1" src="files/img/minicover/<?= $infoManga->name ?>_1.jpg">
                    </div>
                    <div class="col-8 ps-3">
                        <h5><?= $infoManga->name; ?></h5>
                        <?php
                            echo '<span class="badge bg-success user-select-none me-1">'.(new Reference('genre'))->readRef($infoManga->idGenre).'</span>';
                            foreach($infoManga->categories as $element){
                                echo '<span class="badge bg-info user-select-none me-1">'.$element.'</span>';
                            }
                        ?>
                    </div>
                    <div class="col-12 mt-2">
                        <button type="button" class="btn btn-outline-primary col-12" data-id="<?= $elem->id_manga ?>">Ajouter</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    var allButtonAdd = [].slice.call(document.querySelectorAll('[data-id]'));

    // all add button
        allButtonAdd.map(function (element) {
            element.addEventListener('click',function(e){
                myAJAX('inc/addMangaUser.php?id='+element.dataset.id+'&action=remove').then(result => {
                    if(result == 'true'){
                        element.closest('.blocMangaAdd').remove();
                    }else{
                        // Impossible to add, return error message
                    }   
                });
            });
        });

        async function myAJAX(url) {
            let res = await fetch(url)
            let text = await res.text()
            return text
        }        
</script>

<?php
include_once('core/footer.php');
?>