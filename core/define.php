<?php

// Ressources Root
//define('RACINE_SITE', 'https://monsite.com/');
define('__ROOT__', $_SERVER['DOCUMENT_ROOT'].'/_git_manga/'); // Temporaire, avant le server
define('RACINE_SITE', 'http://localhost/_git_manga/');// Temporaire, le temps du dev local
define('CDN', 'core/inc_cdn.php');
define('HEAD', 'core/head.php');
define('FOOTER', 'core/footer.php');
define('_CLASS_', __ROOT__.'core/class/');
define('_API_', __ROOT__.'core/API/');
define('SRC', __ROOT__.'src/');
define('INC', __ROOT__.'inc/');



define('CSS', RACINE_SITE.'core/css/');
define('JS', RACINE_SITE.'core/js/');
define('ICON',RACINE_SITE.'files/icon/');

include('parameter.php');