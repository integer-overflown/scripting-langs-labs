'use strict';

function onProfilePhotoPicked(self) {
    if (self.files && self.files[0]) {
        const fileReader = new FileReader();

        fileReader.onload = contents => {
            document.querySelector('#profilePhotoPreview').setAttribute('src', contents.target.result.toString());
        }

        fileReader.readAsDataURL(self.files[0]);
    }
}

function onSaveProfileClicked() {
    const profileUploadPhoto = document.querySelector('#profileUploadPhoto');
    if (profileUploadPhoto.files.length < 1) {
        alert('Please select a profile picture');
    }
}

const profileUploadPhoto = document.querySelector('#profileUploadPhoto');
const saveProfileButton = document.querySelector('.profile-settings-submit-button');

profileUploadPhoto.addEventListener('change', () => onProfilePhotoPicked(profileUploadPhoto));

saveProfileButton.addEventListener('click', onSaveProfileClicked);
