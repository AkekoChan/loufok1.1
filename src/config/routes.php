<?php

use App\Controllers;

Controllers\Index::bind(["/"], ["GET"]);
Controllers\Index::bind(["/contribute"], ["POST"]); // USER ONLY

Controllers\Cadavre::bind(["/last"], ["GET"]);
// Controllers\Collection::bind(["/cadavre/{int:id}"], ["GET"], "cadavre");

Controllers\Admin::bind(["/create"], ["GET", "POST"]);

Controllers\Profile::bind(["/profile", "/profil"], ["GET"]);

// LOGIN ROUTES
Controllers\Login::bind(["/login"], ["GET"], "template");
Controllers\Login::bind(["/login"], ["POST"], "post");
Controllers\Login::bind(["/logout"], ["GET"], "logout");

Controllers\Admin::bind(["/current"], ["GET"], "current");

Controllers\Controls::bind(["/internal/controls/json"], ["GET"], "index");

Controllers\Api::bind(["/api/cadavres"], ["GET"], "cadavres");
Controllers\Api::bind(["/api/cadavre/{int:id}"], ["GET"], "cadavre");
Controllers\Api::bind(["/api/cadavre/like"], ["POST"], "like");

// Controllers\Notifications::bind(["/subscribe"], ["POST"], "subscribe");