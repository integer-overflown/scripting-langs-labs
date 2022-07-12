'use strict';

function validatePageCredentials() {
    const blankRegex = /^\s*$/;
    const login = document.querySelector('#login').value;
    const password = document.querySelector('#password').value;

    if (blankRegex.test(login) || blankRegex.test(password)) {
        alert('Login and password must be non-blank and non-empty');
        return;
    }

    validateCredentials(login, password).then(redirectUrl => {
        window.location.replace(redirectUrl);
    }, failureReason => {
        alert(failureReason);
    });
}

async function validateCredentials(login, password) {
    return fetch(window.location.href, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `login=${login}&password=${password}`,
        redirect: 'follow'
    }).then(response => {
        if (response.redirected) {
            console.log(`redirected, URL: ${response.url}`);
            return response.url;
        } else if (!response.ok) {
            console.log(`not ok, status: ${response.status}`)
            return response.json().then(errorPayload => Promise.reject(errorPayload['message']));
        }
    }, rejectReason => {
        console.error(`fetch failed: ${rejectReason}`);
        return Promise.reject('Something went wrong, please try again');
    });
}
