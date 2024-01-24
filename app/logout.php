<?php

session_start();

unset($_SESSION['LOGGED_USER']);

http_response_code(302);
header("Location: /"); 
exit();

