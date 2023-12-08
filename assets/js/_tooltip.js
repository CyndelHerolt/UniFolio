import {Tooltip} from 'bootstrap';
document.addEventListener('DOMContentLoaded', (event) => {
    let btn = document.querySelector('#btn-tooltip')
    // bootstrap v5
    if (btn) {
        let tooltip = new Tooltip(btn, {
            title: btn.getAttribute('data-bs-title'),
            placement: btn.getAttribute('data-bs-placement'),
        });
    } else {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

console.log(document.getElementById('tooltip'));
console.log(tooltipList);
    }
});