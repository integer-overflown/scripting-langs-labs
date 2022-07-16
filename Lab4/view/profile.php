<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/profile.php'
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - Lab4</title>
    <link rel="stylesheet" href="index.css" type="text/css">
</head>
<body>
<form action="page_profile.php" method="post" enctype="multipart/form-data">
    <div class="profile-settings-content">
        <div class="profile-upload-photo-section">
            <img class="profile-settings-photo" id="profilePhotoPreview" src="images/ic_placeholder.svg"
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
                           pattern="<?= Profile::NAME_PATTERN ?>" name="<?= Profile::KEY_NAME ?>" required>
                </div>
                <div class="profile-setup-personal-info-component">
                    <label class="profile-setup-personal-info-component-label" for="surnameInput">Surname</label>
                    <input class="profile-setup-settings-key" id="surnameInput" type="text"
                           pattern="<?= Profile::SURNAME_PATTERN ?>" name="<?= Profile::KEY_SURNAME ?>"
                           required>
                </div>
                <div class="profile-setup-personal-info-component">
                    <label class="profile-setup-personal-info-component-label" for="birthDateInput">
                        Date of Birth
                    </label>
                    <input class="profile-setup-settings-key" id="birthDateInput" type="date" max="<?=
                    date('Y-m-d', strtotime('-' . Profile::MIN_ALLOWED_AGE . ' years'))
                    ?>" name="<?= Profile::KEY_BIRTH_DATE ?>" required>
                </div>
            </div>
            <div class="profile-setup-brief-description">
                <p class="profile-setup-personal-info-component-label">Brief description</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc rutrum quam libero, sed euismod ex
                    vehicula
                    sit amet. Vestibulum nec turpis eget risus imperdiet aliquet eget quis purus. Nam mollis augue odio,
                    a
                    elementum ex pellentesque in. Nam vestibulum odio quis eleifend accumsan. Quisque in ornare velit.
                    Donec
                    sed magna blandit, ultrices sem dignissim, consequat nulla. Ut arcu ex, accumsan maximus justo eu,
                    blandit auctor nisl. Phasellus leo sapien, vehicula eu cursus vitae, faucibus non dui. Aliquam erat
                    volutpat. Phasellus vel augue egestas, volutpat ligula quis, suscipit urna. Nulla ac accumsan magna.
                    Aenean sollicitudin, eros nec ornare tincidunt, nunc erat volutpat urna, non placerat urna tellus a
                    eros.
                    Vestibulum mauris mi, mattis at nibh a, suscipit accumsan orci. Aenean venenatis suscipit nisi sit
                    amet
                    vehicula. Nunc luctus eget risus quis pretium. Nulla enim lorem, faucibus eu efficitur non, tempus
                    nec
                    turpis. Integer ut scelerisque massa. Proin ornare, ipsum vulputate condimentum vehicula, sapien
                    orci
                    sodales augue, id ultrices risus purus eget odio. Curabitur a pretium ligula. Curabitur ac commodo
                    lorem, ut fringilla mauris. Curabitur condimentum odio vel ipsum dignissim tristique. Phasellus
                    ullamcorper turpis ut ante malesuada, nec euismod velit porta.
                    Nunc nec magna vel dolor malesuada auctor id ut turpis. Pellentesque non pulvinar elit. Pellentesque
                    tristique volutpat rhoncus. Curabitur leo eros, posuere at sem eget, congue ultrices erat. Phasellus
                    ac
                    fringilla dolor. Praesent interdum dictum congue. In quis accumsan nibh, sit amet varius ligula.
                    Nunc
                    quis purus luctus, lobortis massa eu, luctus turpis. Morbi in enim eu neque placerat interdum nec id
                    mauris. Nullam nec augue eget erat varius vehicula. In hac habitasse platea dictumst. Mauris dolor
                    dui,
                    ultrices quis laoreet nec, mollis non nisi. Sed vel neque ipsum. Proin porta nisi sapien, non
                    interdum
                    nisl suscipit eget.
                    Pellentesque et feugiat arcu, eu finibus sem. Morbi id nulla sed ante rhoncus aliquam. Sed tincidunt
                    nunc id ex varius tristique. Proin et felis eget turpis auctor sollicitudin ac sed metus. Integer
                    hendrerit auctor odio ut varius. Phasellus blandit lorem ac arcu venenatis, id sodales nibh
                    pharetra.
                    Nulla imperdiet arcu metus, a mollis mi laoreet quis. Vestibulum a elit suscipit, bibendum eros nec,
                    dapibus mauris.
                    Vivamus quis nunc imperdiet, efficitur sem eget, suscipit lectus. Proin ac elit id lectus placerat
                    faucibus. Aenean at purus dignissim, bibendum tortor nec, efficitur nulla. Curabitur id justo
                    lobortis,
                    placerat ex nec, mattis tortor. Morbi eget nunc eget urna hendrerit fringilla eu eget nibh. Donec
                    ornare
                    auctor arcu, non eleifend justo suscipit quis. Etiam ac sagittis lorem, feugiat efficitur purus.
                    Phasellus in rhoncus mi. In at justo erat. Nunc congue accumsan faucibus. Morbi tincidunt lorem
                    scelerisque aliquet auctor. Sed in sollicitudin felis. Etiam eu lectus nec massa euismod auctor a
                    vitae
                    tortor. Vivamus rhoncus diam fermentum, bibendum lectus ullamcorper, pharetra ex. Vivamus ac
                    vulputate
                    leo, non tristique ipsum.</p>
            </div>
            <div class="profile-setup-actions-row">
                <button class="profile-settings-submit-button" type="submit">Save
                </button>
            </div>
        </div>
    </div>
</form>
<script src="scripts/profile.js" type="text/javascript"></script>
</body>
</html>
