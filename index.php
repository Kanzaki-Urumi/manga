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
                
            </div>
        </div>
        <hr />
    <?php endforeach; ?>
</div>
<template id="tomeListTemplate">
    <div class="position-relative me-1 mb-2 d-inline-block border-3 border">
        <div class="iconMangaTop cursor-pointer"></div>
        <div class="iconMangaBottom cursor-pointer"></div>
        <img class="" src="" alt="METTRE ALT"/>
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
    function newButtonStateFontawesome(state, id){
        let newElementFontawesome = document.createElement("i");
        newElementFontawesome.classList.add('fa-2x', 'fas', 'bg-dark', 'rounded-circle');
        switch (state) {
            case 'add':
                newElementFontawesome.classList.add('fa-check-circle', 'text-success');
                break;
            case 'wish':
                newElementFontawesome.classList.add('fa-question-circle', 'text-warning');
                break;
            case 'delete':
                newElementFontawesome.classList.add('fa-minus-circle', 'text-danger');
                break;
        }
        newElementFontawesome.addEventListener('click',function(e){
                myAJAX('inc/updateTomeState.php?state='+state+'&id='+id).then(result => {
                    console.log(result)
                });
        });
        return newElementFontawesome;
    }

    // Create bloc Image Tome from template
    function createDivFromTemplate(img, classState, classBorder, topLogo, bottomLogo, id){
        let templateTome = document.querySelector("#tomeListTemplate");
        let clone = document.importNode(templateTome.content, true);
        let imageClone = clone.querySelector("img");
        let mainDivClone = clone.querySelector(".position-relative");
        let topButtonFotawesome = clone.querySelector(".iconMangaTop");
        let bottomButtonFotawesome = clone.querySelector(".iconMangaBottom");
        imageClone.src = 'files/img/minicover/'+img;
        imageClone.classList.add(classState);
        mainDivClone.classList.add(classBorder);
        topButtonFotawesome.appendChild(newButtonStateFontawesome(topLogo, id));
        bottomButtonFotawesome.appendChild(newButtonStateFontawesome(bottomLogo, id));
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
                myAJAX('inc/getListTome.php?id='+id).then(result => {
                    let jsonObject = JSON.parse(result);
                    for (let data in jsonObject) {
                        let dataObject = jsonObject[data];
                        let newBloc = createDivFromTemplate(dataObject.img, dataObject.state, dataObject.border, dataObject.top, dataObject.bottom, dataObject.tome);
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

    async function myAJAX(url) {
        let res = await fetch(url)
        let text = await res.text()
        return text
    }

    // var test = 'delete';
    // myAJAX('inc/updateTomeState.php?a='+test).then(result => {
    //     console.log(result)
    // });

</script>


<?php
include_once('core/footer.php');

?>



    