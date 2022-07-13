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
