<?php   
    use App\Controllers;
    Controllers\Index::bind(["/"], ["GET"], "index");
?>