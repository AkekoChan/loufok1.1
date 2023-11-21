<?php

use App\Controllers;

Controllers\Index::bind(["/"], ["GET"]);
Controllers\Index::bind(["/contribute"], ["POST"]); // USER + ADMIN MAIN PAGE

Controllers\Admin::bind(["/create"], ["GET", "POST"]);

Controllers\Profile::bind(["/profile", "/profil"], ["GET"]);

// LOGIN ROUTES
Controllers\Login::bind(["/login"], ["GET"], "template");
Controllers\Login::bind(["/login"], ["POST"], "post");
Controllers\Login::bind(["/logout"], ["GET"], "logout");
