//-----------------------------------------------------------------------------
//---------------------------------IMAGE-------------------------------------
//-----------------------------------------------------------------------------

// Fonction pour supprimer un champ contenu
export function removeImage(button) {
    button.closest('.image_trace_div').remove();
}

// Fonction pour ajouter un nouveau champ contenu
export function addImage() {
    // Récupération du prototype
    const prototype = document.querySelector('#trace_type_image_contenu').dataset.prototype;
    // Récupération du nombre de champs contenu
    const index = document.querySelectorAll('.image_trace').length;
    console.log(index)
    // Création du nouveau champ contenu
    const newForm = prototype.replace(/__name__/g, index);
    // Ajout du nouveau champ contenu au formulaire
    const formGroup = document.createElement('div');
    formGroup.classList.add('image_trace_div');
    formGroup.classList.add('new_img');
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

// Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ contenu
const addButtonImage = document.querySelector('.add-image');
if (addButtonImage) {
    addButtonImage.addEventListener('click', function () {
        addImage()
    })
}

// Gestionnaire d'événement pour le bouton de suppression d'un champ contenu
document.querySelectorAll('.delete-image').forEach(function (button) {
    button.addEventListener('click', function () {
        removeImage(button);
    });
});


//-----------------------------------------------------------------------------
//---------------------------------PDF-----------------------------------------
//-----------------------------------------------------------------------------

// Fonction pour supprimer un champ contenu
export function removePdf(button) {
    button.closest('.pdf_trace_div').remove();
}

// Fonction pour ajouter un nouveau champ contenu
export function addPdf() {
    // Récupération du prototype et incrémentation du compteur
    const prototype = document.querySelector('#trace_type_pdf_contenu').dataset.prototype;
    const index = document.querySelectorAll('.input-group').length;
    // Création du nouveau champ contenu
    const newForm = prototype.replace(/__name__/g, index);
    // Ajout du nouveau champ contenu au formulaire
    const formGroup = document.createElement('div');
    formGroup.classList.add('pdf_trace_div');
    formGroup.classList.add('new_pdf');
    formGroup.innerHTML = newForm;
    const button = document.createElement('button');
    button.type = 'button';
    button.classList.add('btn', 'btn-danger', 'delete-pdf');
    button.innerHTML = '<i class="fa fa-square-minus"></i>';
    button.style.width = 'fit-content';
    button.style.height = 'fit-content';
    button.addEventListener('click', function () {
        removePdf(button);
    });
    formGroup.appendChild(button);
    const addButton = document.querySelector('.add-pdf');
    addButton.parentNode.insertBefore(formGroup, addButton);
    // console.log('addPdf');
}

const addButtonPdf = document.querySelector('.add-pdf');
if (addButtonPdf) {
    addButtonPdf.addEventListener('click', function () {
        addPdf()
    })
}

document.querySelectorAll('.delete-pdf').forEach(function (button) {
    button.addEventListener('click', function () {
        removePdf(button);
    });
});

//-----------------------------------------------------------------------------
//---------------------------------LIEN----------------------------------------
//-----------------------------------------------------------------------------


// Fonction pour supprimer un champ contenu
export function removeLien(button) {
    button.closest('.lien_trace_div').remove();
}

// Fonction pour ajouter un nouveau champ contenu
export function addLien() {
    // Récupération du prototype
    const prototype = document.querySelector('#trace_type_lien_contenu').dataset.prototype;
    // Récupération du nombre de champs contenu
    const index = document.querySelectorAll('.lien_trace').length;
    // console.log(index)
    // Création du nouveau champ contenu
    const newForm = prototype.replace(/__name__/g, index);
    // Ajout du nouveau champ contenu au formulaire
    const formGroup = document.createElement('div');
    formGroup.classList.add('lien_trace_div');
    formGroup.classList.add('new_lien');
    formGroup.innerHTML = newForm;
    formGroup.innerHTML += (
        '<button type="button" class="btn btn-danger delete-lien">' +
        '<i class="fa-solid fa-square-minus"></i>' +
        '</button>'
    )

    const button = formGroup.querySelector('.delete-lien');
    button.addEventListener('click', function () {
        removeLien(button);
    });
    formGroup.appendChild(button);
    const addButtonLien = document.querySelector('.add-lien');
    addButtonLien.parentNode.insertBefore(formGroup, addButtonLien);
}

const addButtonLien = document.querySelector('.add-lien');
if (addButtonLien) {
    addButtonLien.addEventListener('click', function () {
        addLien()
    })
}

document.querySelectorAll('.lien_trace').forEach(event => {
// Ajouter les classes nécessaires
    event.parentNode.classList.add('lien_trace_div');

    // Créer une nouvelle div qui va englober l'élément .video_trace et le bouton
    const wrapperDiv = document.createElement('div');
    wrapperDiv.style.display = 'flex';
    wrapperDiv.style.flexDirection = 'row';

    // Insérer la nouvelle div juste avant 'video_trace'
    event.parentNode.insertBefore(wrapperDiv, event);

    // Déplacer l'élément .video_trace dans la nouvelle div
    wrapperDiv.appendChild(event);

    // Créer un bouton
    const button = document.createElement('button');
    button.className = 'btn btn-danger delete-lien';
    button.innerHTML = '<i class="fa-solid fa-square-minus"></i>';

    // Ajouter le bouton à la nouvelle div
    wrapperDiv.appendChild(button);
});

document.querySelectorAll('.delete-lien').forEach(function (button) {
    button.addEventListener('click', function () {
        removeLien(button);
    });
});

//-----------------------------------------------------------------------------
//---------------------------------VIDEO---------------------------------------
//-----------------------------------------------------------------------------


// Fonction pour supprimer un champ contenu
export function removeVideo(button) {
    button.closest('.video_trace_div').remove();
}

// Fonction pour ajouter un nouveau champ contenu
export function addVideo() {
    // Récupération du prototype
    const prototype = document.querySelector('#trace_type_video_contenu').dataset.prototype;
    // Récupération du nombre de champs contenu
    const index = document.querySelectorAll('.video_trace').length;
    // console.log(index)
    // Création du nouveau champ contenu
    const newForm = prototype.replace(/__name__/g, index);
    // Ajout du nouveau champ contenu au formulaire
    const formGroup = document.createElement('div');
    formGroup.classList.add('video_trace_div');
    formGroup.classList.add('new_video');
    formGroup.innerHTML = newForm;
    formGroup.innerHTML += (
        '<button type="button" class="btn btn-danger delete-video">' +
        '<i class="fa-solid fa-square-minus"></i>' +
        '</button>'
    )

    const button = formGroup.querySelector('.delete-video');
    button.addEventListener('click', function () {
        removeVideo(button);
    });
    formGroup.appendChild(button);
    const addButtonVideo = document.querySelector('.add-video');
    addButtonVideo.parentNode.insertBefore(formGroup, addButtonVideo);
}

const addButtonVideo = document.querySelector('.add-video');
if (addButtonVideo) {
    addButtonVideo.addEventListener('click', function () {
        addVideo()
    })
}

document.querySelectorAll('.video_trace').forEach(event => {
    // Ajouter les classes nécessaires
    event.parentNode.classList.add('video_trace_div');

    // Créer une nouvelle div qui va englober l'élément .video_trace et le bouton
    const wrapperDiv = document.createElement('div');
    wrapperDiv.style.display = 'flex';
    wrapperDiv.style.flexDirection = 'row';

    // Insérer la nouvelle div juste avant 'video_trace'
    event.parentNode.insertBefore(wrapperDiv, event);

    // Déplacer l'élément .video_trace dans la nouvelle div
    wrapperDiv.appendChild(event);

    // Créer un bouton
    const button = document.createElement('button');
    button.className = 'btn btn-danger delete-video';
    button.innerHTML = '<i class="fa-solid fa-square-minus"></i>';

    // Ajouter le bouton à la nouvelle div
    wrapperDiv.appendChild(button);
});

document.querySelectorAll('.delete-video').forEach(function (button) {
    button.addEventListener('click', function () {
        removeVideo(button);
    });
});


