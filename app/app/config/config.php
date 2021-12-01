<?php

// DB-Params (die Werte sind im Docker-Compose definiert)
// Zugriff auf den Adminer Erfolg auf den Server: mysql und DB "SpesenDB"
define('DB_HOST', 'mysql');
define('DB_USER', 'SpesenUser');
define('DB_PASS', 'admin');
define('DB_NAME', 'SpesenDB');

// Unsere APP-Root 
define('APPROOT', dirname(dirname(__FILE__)));

// Unsere URL-Root
define('URLROOT', 'http://localhost:8000');
