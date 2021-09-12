<?php

require_once('core/define.php');

if(!isset($_GET['id'])|| !is_numeric($_GET['id']))
    header('Location:index.php');


$idManga = (int) $_GET['id'];

try{
    $mangaInfo = new Manga($idManga);
}catch(Exception $e){
    header('Location:index.php');
} 

$mangaInfo->getAllTomes();

include_once('core/header.php');

?>
<div class="col-12 mangaTome-gallery mx-auto">
    <h3 class="text-primary"><?= $mangaInfo->name ?></h3>
    <div class="col-12">
        <span class="badge bg-success user-select-none mb-1"><?= (new Reference('genre'))->readRef($mangaInfo->idGenre) ?></span>
    </div>
    <div class="col-12">
        <?php foreach($mangaInfo->categories as $id => $elem): ?>
            <span class="badge bg-info user-select-none mb-1"><?= $elem ?></span>
        <?php endforeach; ?>
    </div>
    <hr />
    <div class="row justify-content-between">
        <?php foreach($mangaInfo->tomes as $id => $elem): ?>
            <div class="col-6 col-md-4 col-lg-3 text-center mb-5">
                <h4 class="fs-2 text-primary">Tome n° <?= $elem->number ?></h4>
                <!-- <div class="col-12 mb-2 d-flex justify-content-around">
                    <span class="badge rounded-pill bg-success fs-5 px-xl-4"><i class="fa fas fa-check-circle"></i></span>
                    <span class="badge rounded-pill bg-warning fs-5 px-xl-4"><i class="fa fas fa-question-circle"></i></span>
                    <span class="badge rounded-pill bg-danger fs-5 px-xl-4"><i class="fa fas fa-minus-circle"></i></span>
                </div> -->
                <img class="mx-auto img-fluid" alt="Classroom for Heroes Tome 1" src="files/img/cover/<?= $mangaInfo->name.'_'.$elem->number ?>.jpg">
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
  var lazyloadImages = document.querySelectorAll("img.lazy");    
  var lazyloadThrottleTimeout;
  
  function lazyload () {
    if(lazyloadThrottleTimeout) {
      clearTimeout(lazyloadThrottleTimeout);
    }    
    
    lazyloadThrottleTimeout = setTimeout(function() {
        var scrollTop = window.pageYOffset;
        lazyloadImages.forEach(function(img) {
            if(img.offsetTop < (window.innerHeight + scrollTop)) {
              img.src = img.dataset.src;
              img.classList.remove('lazy');
            }
        });
        if(lazyloadImages.length == 0) { 
          document.removeEventListener("scroll", lazyload);
          window.removeEventListener("resize", lazyload);
          window.removeEventListener("orientationChange", lazyload);
        }
    }, 20);
  }
  
  document.addEventListener("scroll", lazyload);
  window.addEventListener("resize", lazyload);
  window.addEventListener("orientationChange", lazyload);
});
</script>
<?php
include_once('core/footer.php');
?>