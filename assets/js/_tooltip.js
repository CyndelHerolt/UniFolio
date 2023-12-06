import { Tooltip } from 'bootstrap';

document.addEventListener('DOMContentLoaded', (event) => {
    let btn = document.querySelector('#my-btn')
    // bootstrap v5
    let tooltip = new Tooltip(btn, {
        title: btn.getAttribute('data-bs-title'),
        placement: btn.getAttribute('data-bs-placement'),
    });
});