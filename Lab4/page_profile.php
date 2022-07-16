<?php

require_once 'model/profile.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        require 'view/profile.php';
        break;
    case 'POST':
        $profile = new Profile();
        $profile->fromKeyArray($_POST);

        if (!$profile->isValid()) {
            error_log('Invalid profile payload in POST request');
            error_log(var_export($_POST));
            http_response_code(400);
            return;
        }

        error_log(var_export($_FILES));

        header("Location: index.php");

        break;
}
