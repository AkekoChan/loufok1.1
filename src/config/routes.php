<?php

use App\Controllers;

Controllers\Index::bind(["/"], ["GET"]);
Controllers\Index::bind(["/contribute"], ["POST"]); // USER ONLY

Controllers\Collection::bind(["/collection"], ["GET"]);
Controllers\Collection::bind(["/cadavre/{int:id}"], ["GET"], "cadavre");

Controllers\Admin::bind(["/create"], ["GET", "POST"]);

Controllers\Profile::bind(["/profile", "/profil"], ["GET"]);

// LOGIN ROUTES
Controllers\Login::bind(["/login"], ["GET"], "template");
Controllers\Login::bind(["/login"], ["POST"], "post");
Controllers\Login::bind(["/logout"], ["GET"], "logout");

Controllers\Notifications::bind(["/subscribe"], ["POST"], "subscribe");