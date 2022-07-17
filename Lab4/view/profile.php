<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/profile.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/login_info.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/site_components.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/page_template.php';

global $headerComponents;
global $footerComponents;

$savedProfile = Profile::fromSession();
$hasProfile = $savedProfile !== null;

$siteHeader = new SiteHeader($headerComponents);
$siteFooter = new SiteFooter($footerComponents);

if (LoginInfo::fromSession() === null) {
    // site body component is ignored if user is not logged in (that is, prompt to log in is displayed instead)
    $siteView = new SiteView($siteHeader, StaticUiComponent::fromString(''), $siteFooter, null);
    echo $siteView->createHtmlView();
    return;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - Lab4</title>
    <link rel="stylesheet" href="index.css" type="text/css">
</head>
<body>
<?= $siteHeader->createHtmlView() ?>
<form action="page_profile.php" method="post" enctype="multipart/form-data">
    <div class="profile-settings-content">
        <div class="profile-upload-photo-section">
            <img class="profile-settings-photo" id="profilePhotoPreview"
                 src="<?= $hasProfile ? $savedProfile->getPicturePath() : 'images/ic_placeholder.svg' ?>"
                 alt="Profile icon placeholder">
            <label for="profileUploadPhoto" class="profile-settings-upload-button">Upload</label>
            <input style="display: none" type="file" id="profileUploadPhoto" name="<?= Profile::KEY_PROFILE_PICTURE ?>"
                   accept="<?= Profile::PHOTO_TYPE_MIME ?>" required>
        </div>
        <div class="profile-setup-section">
            <div class="profile-setup-personal-info">
                <div class="profile-setup-personal-info-component">
                    <label class="profile-setup-personal-info-component-label" for="nameInput">Name</label>
                    <input class="profile-setup-settings-key" id="nameInput" type="text"
                           pattern="<?= Profile::NAME_PATTERN ?>" name="<?= Profile::KEY_NAME ?>"
                           value="<?= $hasProfile ? $savedProfile->getName() : '' ?>" required>
                </div>
                <div class="profile-setup-personal-info-component">
                    <label class="profile-setup-personal-info-component-label" for="surnameInput">Surname</label>
                    <input class="profile-setup-settings-key" id="surnameInput" type="text"
                           pattern="<?= Profile::SURNAME_PATTERN ?>" name="<?= Profile::KEY_SURNAME ?>"
                           value="<?= $hasProfile ? $savedProfile->getSurname() : '' ?>"
                           required>
                </div>
                <div class="profile-setup-personal-info-component">
                    <label class="profile-setup-personal-info-component-label" for="birthDateInput">
                        Date of Birth
                    </label>
                    <input class="profile-setup-settings-key" id="birthDateInput" type="date" max="<?=
                    date('Y-m-d', strtotime('-' . Profile::MIN_ALLOWED_AGE . ' years'))
                    ?>" name="<?= Profile::KEY_BIRTH_DATE ?>"
                           value="<?php
                           if ($hasProfile) {
                               $date = new DateTime();
                               $date->setTimestamp($savedProfile->getBirthDate());
                               echo $date->format("Y-m-d");
                           }
                           ?>"
                           required>
                </div>
            </div>
            <div class="profile-setup-brief-description">
                <label for="profileBriefDescription" class="profile-setup-personal-info-component-label">Brief
                    description</label>
                <textarea id="profileBriefDescription"
                          class="profile-settings-brief-description-input"
                          minlength="<?= Profile::MIN_BRIEF_DESCRIPTION_LENGTH ?>"
                          name="<?= Profile::KEY_BRIEF_DESCRIPTION ?>"
                          placeholder="Enter brief description here&#x2026;"
                          required
                ></textarea>
            </div>
            <div class="profile-setup-actions-row">
                <button class="profile-settings-submit-button" type="submit">Save
                </button>
            </div>
        </div>
    </div>
</form>
<?= $siteFooter->createHtmlView() ?>
<script src="scripts/profile.js" type="text/javascript"></script>
</body>
</html>
