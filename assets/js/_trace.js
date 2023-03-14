//-----------------------------------------------------------------------------
//---------------------------------IMAGE-------------------------------------
//-----------------------------------------------------------------------------

// Fonction pour supprimer un champ contenu
function removeImage(button) {
    button.closest('.input-group').remove();
}

// Fonction pour ajouter un nouveau champ contenu
function addImage() {
    // Récupération du prototype et incrémentation du compteur
    const prototype = document.querySelector('#trace_type_image_contenu').dataset.prototype;
    const index = document.querySelectorAll('.input-group').length;
    // Création du nouveau champ contenu
    const newForm = prototype.replace(/__name__/g, index);
    // Ajout du nouveau champ contenu au formulaire
    const formGroup = document.createElement('div');
    formGroup.classList.add('input-group', 'mb-3');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-end';
    formGroup.innerHTML = newForm;
    const button = document.createElement('button');
    button.type = 'button';
    button.classList.add('btn', 'btn-danger', 'delete-contenu');
    button.innerHTML = '<i class="fa fa-square-minus"></i>';
    button.style.width = 'fit-content';
    button.style.height = 'fit-content';
    button.addEventListener('click', function () {
        removeImage(button);
    });
    formGroup.appendChild(button);
    const addButton = document.querySelector('.add-contenu');
    addButton.parentNode.insertBefore(formGroup, addButton);
    // console.log('addImage');
}

// Gestionnaire d'événement pour le bouton de suppression d'un champ contenu
document.querySelectorAll('.delete-contenu').forEach(function (button) {
    button.addEventListener('click', function () {
        removeImage(button);
    });
});

// Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ contenu
const addButtonImage = document.querySelector('.add-contenu');
if (addButtonImage) {
    addButtonImage.addEventListener('click', function () {
        addImage()
    })
}


//-----------------------------------------------------------------------------
//---------------------------------PDF-----------------------------------------
//-----------------------------------------------------------------------------

// Fonction pour supprimer un champ contenu
function removePdf(button) {
    button.closest('.input-group').remove();
}

// Fonction pour ajouter un nouveau champ contenu
function addPdf() {
    // Récupération du prototype et incrémentation du compteur
    const prototype = document.querySelector('#trace_type_pdf_contenu').dataset.prototype;
    const index = document.querySelectorAll('.input-group').length;
    // Création du nouveau champ contenu
    const newForm = prototype.replace(/__name__/g, index);
    // Ajout du nouveau champ contenu au formulaire
    const formGroup = document.createElement('div');
    formGroup.classList.add('input-group', 'mb-3');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-end';
    formGroup.innerHTML = newForm;
    const button = document.createElement('button');
    button.type = 'button';
    button.classList.add('btn', 'btn-danger', 'delete-contenu');
    button.innerHTML = '<i class="fa fa-square-minus"></i>';
    button.style.width = 'fit-content';
    button.style.height = 'fit-content';
    button.addEventListener('click', function () {
        removePdf(button);
    });
    formGroup.appendChild(button);
    const addButton = document.querySelector('.add-contenu');
    addButton.parentNode.insertBefore(formGroup, addButton);
    // console.log('addPdf');
}

// Gestionnaire d'événement pour le bouton de suppression d'un champ contenu
document.querySelectorAll('.delete-contenu').forEach(function (button) {
    button.addEventListener('click', function () {
        removePdf(button);
    });
});

// Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ contenu
const addButtonPdf = document.querySelector('.add-contenu');
if (addButtonPdf) {
    addButtonPdf.addEventListener('click', function () {
        addPdf()
    })
}


//-----------------------------------------------------------------------------
//---------------------------------LIEN----------------------------------------
//-----------------------------------------------------------------------------

// Fonction pour supprimer un champ contenu
function removeLien(button) {
    button.closest('.input-group').remove();
}

// Fonction pour ajouter un nouveau champ contenu
function addLien() {
    console.log('hello')
    // Récupération du prototype et incrémentation du compteur
    const prototype = document.querySelector('#trace_type_lien_contenu').dataset.prototype;
    const index = document.querySelectorAll('.input-group').length;
    // Création du nouveau champ contenu
    const newForm = prototype.replace(/__name__/g, index);
    // Ajout du nouveau champ contenu au formulaire
    const formGroup = document.createElement('div');
    formGroup.classList.add('input-group', 'mb-3');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-end';
    formGroup.innerHTML = newForm;
    const button = document.createElement('button');
    button.type = 'button';
    button.classList.add('btn', 'btn-danger', 'delete-contenu');
    button.innerHTML = '<i class="fa fa-square-minus"></i>';
    button.style.width = 'fit-content';
    button.style.height = 'fit-content';
    button.addEventListener('click', function () {
        removeLien(button);
    });
    formGroup.appendChild(button);
    const addButton = document.querySelector('.add-contenu');
    addButton.parentNode.insertBefore(formGroup, addButton);
    // console.log('addLien');
}

// Gestionnaire d'événement pour le bouton de suppression d'un champ contenu
document.querySelectorAll('.delete-contenu').forEach(function (button) {
    button.addEventListener('click', function () {
        removeLien(button);
    });
});

// Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ contenu
const addButtonLien = document.querySelector('.add-contenu');
if (addButtonLien) {
    addButtonLien.addEventListener('click', function () {
        addLien()
    })
}

//-----------------------------------------------------------------------------
//---------------------------------VIDEO---------------------------------------
//-----------------------------------------------------------------------------

// Fonction pour supprimer un champ contenu
function removeVideo(button) {
    button.closest('.input-group').remove();
}

// Fonction pour ajouter un nouveau champ contenu
function addVideo() {
    // Récupération du prototype et incrémentation du compteur
    const prototype = document.querySelector('#trace_type_video_contenu').dataset.prototype;
    const index = document.querySelectorAll('.input-group').length;
    // Création du nouveau champ contenu
    const newForm = prototype.replace(/__name__/g, index);
    // Ajout du nouveau champ contenu au formulaire
    const formGroup = document.createElement('div');
    formGroup.classList.add('input-group', 'mb-3');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-end';
    formGroup.innerHTML = newForm;
    const button = document.createElement('button');
    button.type = 'button';
    button.classList.add('btn', 'btn-danger', 'delete-contenu');
    button.innerHTML = '<i class="fa fa-square-minus"></i>';
    button.style.width = 'fit-content';
    button.style.height = 'fit-content';
    button.addEventListener('click', function () {
        removeVideo(button);
    });
    formGroup.appendChild(button);
    const addButton = document.querySelector('.add-contenu');
    addButton.parentNode.insertBefore(formGroup, addButton);
    // console.log('addVideo');
}

// Gestionnaire d'événement pour le bouton de suppression d'un champ contenu
document.querySelectorAll('.delete-contenu').forEach(function (button) {
    button.addEventListener('click', function () {
        removeVideo(button);
    });
});

// Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ contenu
const addButtonVideo = document.querySelector('.add-contenu');
if (addButtonVideo) {
    addButtonVideo.addEventListener('click', function () {
        addVideo()
    })
}