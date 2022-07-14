'use strict';

function getPageProfilePayload() {
    const payload = {};
    for (const inputElement of document.querySelectorAll(".profile-setup-settings-key")) {
        if (inputElement.checkValidity()) {
            payload[inputElement.dataset.profileKey] = inputElement.value;
        } else {
            inputElement.reportValidity();
            return null;
        }
    }
    return payload;
}

function updateProfileSettings() {
    const payload = getPageProfilePayload();
    console.log(payload);
}

function onProfilePhotoPicked(self) {
    if (self.files && self.files[0]) {
        const fileReader = new FileReader();

        fileReader.onload = contents => {
            document.querySelector('#profilePhotoPreview').setAttribute('src', contents.target.result.toString());
        }

        fileReader.readAsDataURL(self.files[0]);
    }
}

const profileUploadPhoto = document.querySelector('#profileUploadPhoto');

profileUploadPhoto.addEventListener('change', () => onProfilePhotoPicked(profileUploadPhoto));
