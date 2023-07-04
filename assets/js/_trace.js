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
    formGroup.classList.add('input-group', 'mb-3', 'pdf_trace_div');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-end';
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
    formGroup.classList.add('input-group', 'mb-3', 'lien_trace_div');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-end';
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
    formGroup.classList.add('input-group', 'mb-3', 'video_trace_div');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-end';
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



