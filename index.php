<?php
require_once('core/define.php');

include_once('core/header.php');

// var_dump($userObj->manga);
// echo($userObj->test);
?>


<div class="col-12 collection-gallery mx-auto">
    <div class="row pb-3">
        <div class="col-12">
            <span class="btn btn-pourpre" type="button" id="hideAll">Cacher Tous</span>
            <span class="btn btn-pourpre" type="button" id="showAll">Montrer Tous</span>
            <span class="btn btn-pourpre" type="button">Ajouter un Manga</span>
        </div>
    </div>
    <?php foreach($userObj->manga as $id => $elem):?>
        <?php $MangaInfo = new Manga($id); ?>
        <div class="row">
            <div class="col-12">
                <span class="me-1"><?= $MangaInfo->name ?></span><span class="actionCollapseTome text-info cursor-pointer" data-id="<?= $id ?>"><i class="fas fa-chevron-right"></i></span><br />
                <span class="badge bg-success"><?= (new Reference('genre'))->readRef($MangaInfo->idGenre) ?></span>
                <?php foreach($MangaInfo->categories as $categorieLabel): ?>
                    <span class="badge bg-primary"><?= $categorieLabel ?></span>
                <?php endforeach; ?>
            </div>
            <div class="col-12 pt-2 collapse tomeCollapse" id="collapseTome<?= $id ?>">
                <?php foreach($MangaInfo->tomes as $value): ?>
                    <div class="position-relative me-1 mb-2 d-inline-block border-3 border border-<?= getClassBorderColorStateTome($elem[$value->idTome]??0) ?>">
                        <div class="iconMangaTop"><i class="fa-2x fas fa-check-circle text-success"></i></div>
                        <div class="iconMangaBottom"><i class="fa-2x fas fa-minus-circle text-danger"></i></div>
                        <img class="<?= getClassStateTome($elem[$value->idTome]??0) ?>" data-src="files/img/minicover/<?= $value->image ?>" src="" alt="<?= $MangaInfo->name.' Tome '.$value->number ?>"/>
                    </div>
                <?php endforeach; ?>        
            </div>
        </div>
        <hr />
    <?php endforeach; ?>
</div>
<script>

    var allCollapseElement = [].slice.call(document.querySelectorAll('.tomeCollapse'));
    var collapseButton = [].slice.call(document.querySelectorAll('.actionCollapseTome'));
    function removeCollapseState(){
        allCollapseElement.map(function (collapseEach) {
            collapseEach.classList.remove('hide');
            collapseEach.classList.remove('show');
        });
    }

    function addCollapseState(state){
        allCollapseElement.map(function (collapseEach) {
            collapseEach.classList.add(state);
        });
    }

    function buttonCollapseState(id, state = false){
        let buttonChevron = document.querySelector('[data-id="'+id+'"] > i');
        if(buttonChevron.classList.contains('fa-chevron-right') || state == 'show'){
            buttonChevron.classList.remove('fa-chevron-right');
            buttonChevron.classList.add('fa-chevron-down');
            let listingImageDataSrc = [].slice.call(document.querySelectorAll('#collapseTome' + id + ' img'));
            listingImageDataSrc.map(function (imageData) {
                imageData.src = imageData.dataset.src;
            });
        }else if(!buttonChevron.classList.contains('fa-chevron-right') || state == 'hide'){
            buttonChevron.classList.remove('fa-chevron-down');
            buttonChevron.classList.add('fa-chevron-right');
        }
    }
    
    // Show or Hide collapse element
    function collapseElement(elementCollapsible, varToggle=true){
        let elementToCollapse = new bootstrap.Collapse(elementCollapsible,{toggle:varToggle});
    };

    // Apply function to all Collapsible element tome
    function collapseAllTome(varBisToggle = true){
        allCollapseElement.map(function (collapseEach) {
            collapseElement(collapseEach, varBisToggle);
        });
    };
    
    collapseButton.map(function (e){
        e.addEventListener("click", function(){
            let collapseTomeTarget = document.querySelector('#collapseTome'+e.dataset.id);  
            collapseElement(collapseTomeTarget);
            buttonCollapseState(e.dataset.id);
        });
    })

    document.querySelector('#hideAll').addEventListener("click", function(){
        removeCollapseState();
        addCollapseState('hide');
        collapseButton.map(function (element){
            buttonCollapseState(element.dataset.id, 'hide');
        });
    });
    document.querySelector('#showAll').addEventListener("click", function(){
        removeCollapseState();
        addCollapseState('show');
        collapseButton.map(function (element){
            buttonCollapseState(element.dataset.id, 'show');
        });
    });

    async function myAJAX(url) {
        let res = await fetch(url)
        let text = await res.text()
        return text
    }

    var test = 'delete';
    myAJAX('inc/updateTomeState.php?a='+test).then(result => {
        console.log(result)
    });

</script>


<?php
include_once('core/footer.php');

?>



    