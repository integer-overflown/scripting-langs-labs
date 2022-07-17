<?php

require_once 'model/profile.php';

const FILE_UPLOAD_DIR = 'file_uploads';
const FILE_NAME_MAX_LEN = 255;

function validateUploadFileName(string $name): bool
{
    return strlen($name) <= FILE_NAME_MAX_LEN && preg_match('/^[\da-zA-Z\-._]+$/i', $name) === 1;
}

function parseDateToUnixTimestamp(string $dateString): ?int
{
    try {
        $date = new DateTimeImmutable($dateString);
    } catch (Exception $e) {
        error_log("Failed to parse '$dateString': " . $e->getMessage());
        return null;
    }
    return $date->getTimestamp();
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        require 'view/profile.php';
        break;
    case 'POST':
        $profile = new Profile();
        $profile->fromKeyArray($_POST);

        if (!(isset($_POST[Profile::KEY_NAME])
            && isset($_POST[Profile::KEY_SURNAME])
            && isset($_POST[Profile::KEY_BIRTH_DATE])
            && isset($_POST[Profile::KEY_BRIEF_DESCRIPTION])
        )) {
            error_log('Invalid POST payload');
            error_log(var_export($_POST, true));
            http_response_code(400);
            return;
        }

        $birthDate = parseDateToUnixTimestamp($_POST[Profile::KEY_BIRTH_DATE]);

        if ($birthDate === null) {
            error_log('Birth date ' . $_POST[Profile::KEY_BIRTH_DATE] . ' is not a valid date');
            http_response_code(400);
            return;
        }

        $profile
            ->setName($_POST[Profile::KEY_NAME])
            ->setSurname($_POST[Profile::KEY_SURNAME])
            ->setBirthDate($birthDate)
            ->setBriefDescription($_POST[Profile::KEY_BRIEF_DESCRIPTION]);

        if (!$profile->isValid()) {
            error_log('Invalid profile payload in POST request');
            error_log(var_export($_POST, true));
            http_response_code(400);
            return;
        }

        $pictureInfo = $_FILES[Profile::KEY_PROFILE_PICTURE];

        // has upload succeeded?
        if ($pictureInfo['error'] != UPLOAD_ERR_OK) {
            error_log('File upload failed');
            http_response_code(500);
            return;
        }

        $tmpPath = $pictureInfo['tmp_name'];

        $imageInfo = getimagesize($tmpPath);

        // is file an image?
        if ($imageInfo === false) {
            error_log('Invalid attempt to upload non-image file: ' . $pictureInfo['name']);
            http_response_code(400);
            return;
        }

        // does file have valid name?
        if (!validateUploadFileName($pictureInfo['name'])) {
            error_log('File name validation failed: ' . $pictureInfo['name']);
            http_response_code(400);
            return;
        }

        if (!is_dir(FILE_UPLOAD_DIR)) {
            error_log('No such directory: ' . FILE_UPLOAD_DIR);
            http_response_code(500);
            return;
        }

        $processedFileName = basename($pictureInfo["name"]);

        $destination = FILE_UPLOAD_DIR . "/$processedFileName";

        if (move_uploaded_file($tmpPath, $destination) === false) {
            error_log("Failed to move file to upload dir: $processedFileName");
            http_response_code(500);
            return;
        }

        $profile->setPicturePath($destination);
        $profile->writeToSession();

        header("Location: index.php");

        break;
}
