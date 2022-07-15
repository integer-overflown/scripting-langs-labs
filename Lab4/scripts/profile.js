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

const profileUploadPhoto = document.querySelector('#profileUploadPhoto');

profileUploadPhoto.addEventListener('change', () => onProfilePhotoPicked(profileUploadPhoto));
