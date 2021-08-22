<?php
require_once('core/define.php');

include_once('core/header.php');
?>

<div class="col-12 collection-gallery mx-auto">
    <span class="btn btn-primary" type="button" id="hideAll">Bouton #1</span><br />
    <span class="btn btn-primary" type="button" id="showAll">Bouton #1</span><br />
    <?php foreach($userObj->manga as $id => $elem):?>
        <?php $MangaInfo = new Manga($id); ?>
        <div class="row">
            <div class="col-12">
                <span class="me-1"><?= $MangaInfo->name ?></span><span class="btn btn-primary actionCollapseTome" type="button" data-id="<?= $id ?>">Bouton #<?= $id ?></span><br />
                <span class="badge bg-success"><?= (new Reference('genre'))->readRef($MangaInfo->idGenre) ?></span>
                <?php foreach($MangaInfo->categories as $categorieLabel): ?>
                    <span class="badge bg-primary"><?= $categorieLabel ?></span>
                <?php endforeach; ?>
            </div>
            <div class="col-12 pt-2 collapse tomeCollapse" id="collapseTome<?= $id ?>">
                <?php foreach($MangaInfo->tomes as $value): ?>
                    <div class="me-1 mb-2 d-inline-block border-3 border border-<?= getClassBorderColorStateTome($elem[$value->idTome]??0) ?>">
                        <img class="<?= getClassStateTome($elem[$value->idTome]??0) ?>" src="files/img/minicover/<?= $value->image ?>" alt="<?= $MangaInfo->name.' Tome '.$value->number ?>"/>
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
        });
    })

    document.querySelector('#hideAll').addEventListener("click", function(){
        removeCollapseState();
        addCollapseState('hide');
    });
    document.querySelector('#showAll').addEventListener("click", function(){
        removeCollapseState();
        addCollapseState('show');
    });
</script>


<?php
include_once('core/footer.php');

?>



    