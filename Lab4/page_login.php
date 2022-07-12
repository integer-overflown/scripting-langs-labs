<?php
require_once 'data_store.php';
require_once 'page_template.php';
require_once 'site_components.php';
require_once 'model/login_error.php';
require_once 'model/login_info.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (LoginInfo::fromSession() === null) {
            $file = file_get_contents('static/login.html');
            if ($file === false) {
                trigger_error("Cannot find login page html", E_USER_ERROR);
            }
            echo $file;
        } else {
            header("Location: index.php");
        }
        break;
    case 'POST':
        $ds = new DataStore();
        $credentials = $ds->getRegisteredCredentials();

        if (!array_key_exists('login', $_POST)) {
            $error = new LoginError('Login is required parameter');
            http_response_code(400);
            echo $error->toJson();
            break;
        }

        if (!array_key_exists('password', $_POST)) {
            $error = new LoginError('Password is required parameter');
            http_response_code(400);
            echo $error->toJson();
            break;
        }

        if (!in_array([$_POST['login'], $_POST['password']], $credentials)) {
            $error = new LoginError('Invalid login or password');
            http_response_code(400);
            echo $error->toJson();
            break;
        }

        header('Location: index.php');
        http_response_code(302);

        break;
    default:
        http_response_code(405); // 405 Method Not Supported
        break;
}
