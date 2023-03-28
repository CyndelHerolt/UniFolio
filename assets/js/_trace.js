//-----------------------------------------------------------------------------
//---------------------------------IMAGE-------------------------------------
//-----------------------------------------------------------------------------

document.querySelectorAll('.image_trace').forEach(event => {
    console.log(event)
    event.parentNode.classList.add('image_trace_div')
    event.parentNode.classList.add('input-group', 'mb-3');
    event.style.display = 'flex';
    event.style.alignItems = 'flex-end';
    // pour chaque bloc existant, on ajoute les boutons pour manipuler le form
    event.parentNode.innerHTML += (
        '<button type="button" class="btn btn-danger delete-image">' +
        '<i class="fa-solid fa-square-minus"></i>' +
        '</button>'
    )
})


// Fonction pour supprimer un champ contenu
function removeImage(button) {
    button.closest('.image_trace_div').remove();
}

// Fonction pour ajouter un nouveau champ contenu
function addImage() {
    // Récupération du prototype
    const prototype = document.querySelector('#trace_type_image_contenu').dataset.prototype;
    // Récupération du nombre de champs contenu
    const index = document.querySelectorAll('.image_trace').length;
    // console.log(index)
    // Création du nouveau champ contenu
    const newForm = prototype.replace(/__name__/g, index);
    // Ajout du nouveau champ contenu au formulaire
    const formGroup = document.createElement('div');
    formGroup.classList.add('input-group', 'mb-3', 'image_trace_div');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-end';
    formGroup.innerHTML = newForm;
    formGroup.innerHTML += (
        '<button type="button" class="btn btn-danger delete-image">' +
        '<i class="fa-solid fa-square-minus"></i>' +
        '</button>'
    )

    const button = formGroup.querySelector('.delete-image');
    button.addEventListener('click', function () {
        removeImage(button);
    });
    formGroup.appendChild(button);
    const addButtonImg = document.querySelector('.add-image');
    addButtonImg.parentNode.insertBefore(formGroup, addButtonImg);
}

// Gestionnaire d'événement pour le bouton de suppression d'un champ contenu
document.querySelectorAll('.delete-image').forEach(function (button) {
    button.addEventListener('click', function () {
        removeImage(button);
    });
});

// Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ contenu
const addButtonImage = document.querySelector('.add-image');
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

