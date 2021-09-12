<?php
require_once('core/define.php');

include_once('core/header.php');

if(isset($_GET['type'])){
    switch($_GET['type']):
        case 'all': $userObj->getLibraryByState(); $ajaxTomeStateSearch = 'all'; break;
        case 'have': $userObj->getLibraryByState([],[],1); $ajaxTomeStateSearch = 'have'; break;
        case 'wish': $userObj->getLibraryByState([],[],2); $ajaxTomeStateSearch = 'wish'; break;
        case 'havent': $userObj->getLibraryByState([],[],0); $ajaxTomeStateSearch = 'havent'; break;
        default: $ajaxTomeStateSearch = 'all';
    endswitch;
}

?>
<div class="col-12 collection-gallery mx-auto">
    <div class="row pb-3">
        <div class="col-12">
            <span class="btn btn-pourpre" type="button" id="hideAll">Cacher Tous</span>
            <span class="btn btn-pourpre" type="button" id="showAll">Montrer Tous</span>
        </div>
        <div class="col-12">
            <h5>Genre</h5>
            <?php foreach((new Reference('genre'))->getTableList() as $elem): ?>
                <span class="badge bg-success cursor-pointer user-select-none"><?= $elem->label ?></span>
            <?php endforeach; ?>
        </div>
        <div class="col-12">
            <h5>Catégorie</h5>
            <?php foreach((new Reference('categorie'))->getTableList() as $elem): ?>
                <span class="badge bg-info cursor-pointer user-select-none"><?= $elem->label ?></span>
            <?php endforeach; ?>
        </div>
    </div>
    <?php foreach($userObj->manga as $id => $elem):?>
        <?php $MangaInfo = new Manga($id); ?>
        <div class="row">
            <div class="col-12">
                <h3 class="me-1"><?= $MangaInfo->name ?></span><span class="actionCollapseTome text-info cursor-pointer" data-id="<?= $id ?>">&nbsp;<i class="fas fa-chevron-right"></i></h3>
                <span class="badge bg-success"><?= (new Reference('genre'))->readRef($MangaInfo->idGenre) ?></span>
                <?php foreach($MangaInfo->categories as $categorieLabel): ?>
                    <span class="badge bg-primary"><?= $categorieLabel ?></span>
                <?php endforeach; ?>
            </div>
            <div class="col-12 pt-2 collapse tomeCollapse" id="collapseTome<?= $id ?>">
                
            </div>
        </div>
        <hr />
    <?php endforeach; ?>
</div>
<template id="tomeListTemplate">
    <div class="position-relative me-1 mb-2 d-inline-block border-5 border">
        <div class="iconMangaTop cursor-pointer"></div>
        <div class="iconMangaBottom cursor-pointer"></div>
        <img alt="METTRE ALT"/>
    </div>
</template>

<script>

    var allCollapseElement = [].slice.call(document.querySelectorAll('.tomeCollapse'));
    var collapseButton = [].slice.call(document.querySelectorAll('.actionCollapseTome'));

    // Remove state of Collapse
    function removeCollapseState(){
        allCollapseElement.map(function (collapseEach) {
            collapseEach.classList.remove('hide');
            collapseEach.classList.remove('show');
        });
    }

    // Change the Collapse State
    function addCollapseState(state){
        allCollapseElement.map(function (collapseEach) {
            collapseEach.classList.add(state);
        });
    }

    // Create Button Edit State Tome
    function newButtonStateFontawesome(topElement, bottomElement, state, id){
        topElement.innerHTML = ''; // Clean incons
        bottomElement.innerHTML = ''; // Clean incons
        let newTopFontawesome = document.createElement("i");
        let newBottomFontawesome = document.createElement("i");
        let parentElement = topElement.closest('.border-5');
        let nextTopState, nextBottomState, borderClass, imageGey;
        newTopFontawesome.classList.add('fa-2x', 'fas', 'bg-dark', 'rounded-circle');
        newBottomFontawesome.classList.add('fa-2x', 'fas', 'bg-dark', 'rounded-circle');

        // Defined icon and class of all element of the statement
        switch (state) {
            case 'add':
                newTopFontawesome.classList.add('fa-question-circle', 'text-warning');
                newBottomFontawesome.classList.add('fa-minus-circle', 'text-danger');
                nextTopState = 'wish';
                nextBottomState = 'delete';
                borderClass = 'border-success';
                imageGey = 'haveTome';
                break;
            case 'wish':
                newTopFontawesome.classList.add('fa-check-circle', 'text-success');
                newBottomFontawesome.classList.add('fa-minus-circle', 'text-danger');
                nextTopState = 'add';
                nextBottomState = 'delete';
                borderClass = 'border-warning';
                imageGey = 'haventTome';
                break;
            case 'delete':
                newTopFontawesome.classList.add('fa-check-circle', 'text-success');
                newBottomFontawesome.classList.add('fa-question-circle', 'text-warning');
                nextTopState = 'add';
                nextBottomState = 'wish';
                borderClass = 'border-danger';
                imageGey = 'haventTome';
                break;
        }
        // New listener self
        newTopFontawesome.addEventListener('click',function(e){
                myAJAX('inc/updateTomeState.php?state='+nextTopState+'&id='+id).then(result => {
                    newButtonStateFontawesome(topElement, bottomElement, nextTopState, id);
                });
        });
        newBottomFontawesome.addEventListener('click',function(e){
                myAJAX('inc/updateTomeState.php?state='+nextBottomState+'&id='+id).then(result => {
                    newButtonStateFontawesome(topElement, bottomElement, nextBottomState, id);
                });
        });

        // Clean and add class before add all content
        parentElement.classList.remove('border-success', 'border-warning', 'border-danger');
        parentElement.classList.add(borderClass);
        parentElement.querySelector('img').classList.remove('haventTome', 'haveTome');
        parentElement.querySelector('img').classList.add(imageGey);
        topElement.appendChild(newTopFontawesome);
        bottomElement.appendChild(newBottomFontawesome);
    }

    // Create bloc Image Tome from template
    function createDivFromTemplate(img, classState, classBorder, current, id){
        let templateTome = document.querySelector("#tomeListTemplate");
        let clone = document.importNode(templateTome.content, true);
        let imageClone = clone.querySelector("img");
        let mainDivClone = clone.querySelector(".position-relative");
        let topButtonFotawesome = clone.querySelector(".iconMangaTop");
        let bottomButtonFotawesome = clone.querySelector(".iconMangaBottom");
        imageClone.src = 'files/img/minicover/'+img;
        imageClone.classList.add(classState);
        mainDivClone.classList.add(classBorder);
        newButtonStateFontawesome(topButtonFotawesome, bottomButtonFotawesome, current, id);
        return clone;
    }

    // Toggle event Collapse, and load Content tome when not already load
    function buttonCollapseState(id, state = false){
        let buttonChevron = document.querySelector('[data-id="'+id+'"] > i');
        if(buttonChevron.classList.contains('fa-chevron-right') || state == 'show'){
            buttonChevron.classList.remove('fa-chevron-right');
            buttonChevron.classList.add('fa-chevron-down');

            let divTome = document.querySelector('#collapseTome' + id);
            if(divTome.children.length == 0){
                let newElementDiv = document.createElement("div");
                myAJAX('inc/getListTome.php?id='+id+'&state=<?= $ajaxTomeStateSearch ?>').then(result => {
                    let jsonObject = JSON.parse(result);
                    for (let data in jsonObject) {
                        let dataObject = jsonObject[data];
                        let newBloc = createDivFromTemplate(dataObject.img, dataObject.state, dataObject.border, dataObject.current, dataObject.tome);
                        newElementDiv.appendChild(newBloc);
                    }
                });
                divTome.appendChild(newElementDiv);
            }
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
    
    // Listener Event on Title Manga
    collapseButton.map(function (e){
        e.addEventListener("click", function(){
            let collapseTomeTarget = document.querySelector('#collapseTome'+e.dataset.id);  
            collapseElement(collapseTomeTarget);
            buttonCollapseState(e.dataset.id);
        });
    })

    // Button Hide All
    document.querySelector('#hideAll').addEventListener("click", function(){
        removeCollapseState();
        addCollapseState('hide');
        collapseButton.map(function (element){
            buttonCollapseState(element.dataset.id, 'hide');
        });
    });

    // Button Show All
    document.querySelector('#showAll').addEventListener("click", function(){
        removeCollapseState();
        addCollapseState('show');
        collapseButton.map(function (element){
            buttonCollapseState(element.dataset.id, 'show');
        });
    });

    // Small fonction ajax
    async function myAJAX(url) {
        let res = await fetch(url)
        let text = await res.text()
        return text
    }
</script>


<?php
include_once('core/footer.php');

?>



    