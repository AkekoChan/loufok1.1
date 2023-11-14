<?php   
    use App\Controllers;

    Controllers\Index::bind(["/"], ["GET"], "index");

    // LOGIN ROUTES
    Controllers\Login::bind(["/login"], ["GET"], "template");
    Controllers\Login::bind(["/login"], ["POST"], "post");
    Controllers\Login::bind(["/logout"], ["GET"], "logout");
?>