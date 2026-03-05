var toastLiveExample = document.getElementById('liveToast')

if (toastLiveExample) {
    toastLiveExample.classList.add('show');
}


export function createAndShow(type, text) {
    const uniqueId = 'unique-alert-id';
    const html = `<div id="${uniqueId}" class="alert alert-${type} alert-dismissible fade show" role="alert">${text}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`

    const div = document.querySelector('.test');
    div.innerHTML = html;

    // Supprimer l'alerte après 5 secondes
    setTimeout(() => {
        const alert = document.getElementById(uniqueId);
        if (alert) {
            alert.remove();
        }
    }, 3000);
}
