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



















//-----------------------------------------------------------------------------
//----------------------------EXPERIENCES--------------------------------------
//-----------------------------------------------------------------------------

document.querySelectorAll('.experience').forEach((event) => {
    // event = .experience (chaque bloc experience)
    console.log(event)
    // pour chaque bloc experience existant, on ajoute les boutons pour manipuler le form
    event.innerHTML += (
        '<div><button type="button" class="btn btn-primary add-experience-activite">' +
        'Ajouter une activité <i class="fa fa-square-plus"></i>' +
        '</button></div>' +
        '<br>' +
        '<button type="button" class="btn btn-danger delete-experience">' +
        'Supprimer l\'expérience <i class="fa-solid fa-square-minus"></i>' +
        '</button>'
    )
})

// Fonction pour ajouter un nouveau champ experience
function addExperience(event) {
    // console.log(event);
    // Récupération du prototype
    const prototype = document.querySelector('#cv_experience').dataset.prototype;
    // Récupération du nombre d'expériences existantes
    const index = document.querySelectorAll('.experience').length;
    // Création du nouveau form experience
    const newForm = prototype.replace(/__name__/g, index);
    // Création d'un bloc experience pour le nouveau form
    const formGroup = document.createElement('div');

    // Injection du prototype et des boutons dans le nouveau bloc experience
    formGroup.innerHTML = newForm;
    formGroup.querySelector('.experience').innerHTML += (
        '<div><button type="button" class="btn btn-primary add-experience-activite">' +
        'Ajouter une activité <i class="fa fa-square-plus"></i>' +
        '</button></div>' +
        '<br>' +
        '<button type="button" class="btn btn-danger delete-experience">' +
        'Supprimer l\'expérience <i class="fa-solid fa-square-minus"></i>' +
        '</button>'
    )

    // Injection du nouveau bloc experience
    event.parentNode.insertBefore(formGroup, event);
    console.log(document.querySelectorAll('.experience'));

    // Attacher un gestionnaire d'événements au nouveau bouton "delete-experience"
    formGroup.querySelector('.delete-experience').addEventListener('click', function () {
        removeExperience(this);
    });
    // Attacher un gestionnaire d'événements au nouveau bouton "add-experience-activite"
    formGroup.querySelector('.add-experience-activite').addEventListener('click', function () {
        addExperienceActivite(this);
    });
}

// Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ experience
const addButtonExperience = document.querySelector('.add-experience');
if (addButtonExperience) {
    addButtonExperience.addEventListener('click', function (event) {
        addExperience(event.target);
    })
}

// Fonction pour supprimer un champ experience
function removeExperience(event) {
    event.closest('.experience').remove();
    console.log(event);
}

// Gestionnaire d'événement pour le bouton de suppression d'un champ experience
document.querySelectorAll('.delete-experience').forEach(function (event) {
    event.addEventListener('click', function () {
        console.log(event);
        removeExperience(event);
    });
});

// Fonction pour ajouter un nouveau champ experience activite
function addExperienceActivite(event) {
    console.log(event);
    const formGroup = document.createElement('div');
    formGroup.classList.add('experience-activite');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-end';
    formGroup.innerHTML += '<label for="activite" class="form-label">Activité</label>';
    formGroup.innerHTML += '<input type="text" name="activite" id="a" class="form-control">';
    const button = document.createElement('button');
    button.type = 'button';
    button.classList.add('btn', 'btn-danger', 'delete-experience-activite');
    button.innerHTML = '<i class="fa fa-square-minus"></i>';
    button.style.width = 'fit-content';
    button.style.height = 'fit-content';
    button.addEventListener('click', function () {
        removeExperienceActivite(button);
    });
    formGroup.appendChild(button);
    // const addButtonExperienceActivite = document.querySelector('.add-experience-activite');
    event.parentNode.insertBefore(formGroup, event);
}

// Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ experience activite
document.querySelectorAll('.add-experience-activite').forEach(function (event) {
    event.addEventListener('click', function () {
        addExperienceActivite(event);
    });
});

// Gestionnaire d'événement pour le bouton de suppression d'un champ activite
document.querySelectorAll('.delete-experience-activite').forEach(function (button) {
    button.addEventListener('click', function () {
        removeExperienceActivite(button);
    });
});

// Fonction pour supprimer un champ formation
function removeExperienceActivite(button) {
    button.closest('.experience-activite').remove();
}