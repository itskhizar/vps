<?php

define("DB_SERVER", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "vps");


// define("DB_SERVER", "localhost");
// define("DB_USER", "u916098885_academy");
// define("DB_PASS", "Asad@1234A");
// define("DB_NAME", "u916098885_academy");

/**
 * Database Table Constants - these constants
 * hold the names of all the database tables used
 * in the script.
 */
define("TBL_USERS", "users");
define("TBL_ACTIVE_USERS", "active_users");
define("TBL_ACTIVE_GUESTS", "active_guests");
define("TBL_BANNED_USERS", "banned_users");


define("ADMIN_NAME", "admin");    //1. admin conrol all
define("GUEST_NAME", "Guest");
define("ADMIN_LEVEL", 9);        // 2. admin level .. control the master
define("MASTER_LEVEL", 8);       // 3. master level .. master control the agent
define("AGENT_LEVEL", 1);       // 4. agent level .. agent control the member
define("AGENT_MEMBER_LEVEL", 2); // 5. agent member level .. member control his/her own account
define("GUEST_LEVEL", 0);        // 6. guest level .. guest only control himself


define("TRACK_VISITORS", true);


define("USER_TIMEOUT", 10);
define("GUEST_TIMEOUT", 5);


define("COOKIE_EXPIRE", 60 * 60 * 24);  //now for 1 day
define("COOKIE_PATH", "/");  //Avaible in whole domain


define("EMAIL_FROM_NAME", "ARMAN G. DE CASTRO");
define("EMAIL_FROM_ADDR", "armandecastro@gmail.com");
define("EMAIL_WELCOME", false);


define("ALL_LOWERCASE", false);
?>