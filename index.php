<?php
namespace index;
require_once  __DIR__.'/vendor/autoload.php';

use Alerts\Alerts;
use Core\Router;
use Datainterface\Database;
use Datainterface\Tables;


@session_start();

//Admin nav
//require_once 'Views/DefaultViews/nav.php';

//website nav
require_once 'Views/navbar.view.php';

global $connection;
$connection = Database::database();
if(!Tables::tablesExists()){
    Tables::installTableRequired();
}
?>
<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <?php
        /**
         * Routing is starting from here Router::router();
         */
        Router::router(true, "developement");

        if(isset($_SESSION['message']['route']) && !empty($_SESSION['message']['route'])){
            echo Alerts::alert('danger', $_SESSION['message']['route']);
            $_SESSION['message']['route'] = "";
        }
        ?>
    </div>
    <script src="./Js/scrpt.js"></script>
    <script src="./Js/main.js"></script>
</main>
<?php
//Admin footer
//require_once 'Views/DefaultViews/footer.php';

//Website footer
require_once 'Views/footer.view.php';
?>
