'use strict';

function removeProductFromCart(productId) {
    if (isNaN(+productId)) {
        console.error(`Invalid product id ${productId}`);
        return;
    }
    const xhr = new XMLHttpRequest();
    xhr.onload = () => {
        const pageBody = document.querySelector('.page-body-view');
        // it's arguable what way is the best, but this is the easiest :)
        pageBody.innerHTML = xhr.responseText;
    };
    xhr.onerror = () => {
        alert(`Failed to delete product (id = ${productId})`);
    }

    xhr.open('DELETE', window.location.href + `?productId=${productId}`);
    xhr.send();
}
