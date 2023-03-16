//TODO: trouver pourquoi le btn d'ajout ne fonctionne pas lors de l'edition d'un cv qui a deja des champs
//-----------------------------------------------------------------------------
//---------------------------------SOFT SKILLS---------------------------------
//-----------------------------------------------------------------------------

// Fonction pour supprimer un champ soft_skill
function removeSoftSkill(button) {
    button.closest('.input-group').remove();
}

// Fonction pour ajouter un nouveau champ soft_skill
function addSoftSkill() {
    // Récupération du prototype et incrémentation du compteur
    const prototype = document.querySelector('#cv_soft_skills').dataset.prototype;
    const index = document.querySelectorAll('.input-group').length;
    // Création du nouveau champ soft_skill
    const newForm = prototype.replace(/__name__/g, index);
    // Ajout du nouveau champ soft_skill au formulaire
    const formGroup = document.createElement('div');
    formGroup.classList.add('input-group', 'mb-3');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-end';
    formGroup.innerHTML = newForm;
    const button = document.createElement('button');
    button.type = 'button';
    button.classList.add('btn', 'btn-danger', 'delete-soft-skill');
    button.innerHTML = '<i class="fa fa-square-minus"></i>';
    button.style.width = 'fit-content';
    button.style.height = 'fit-content';
    button.addEventListener('click', function () {
        removeSoftSkill(button);
    });
    formGroup.appendChild(button);
    const addButtonSoft = document.querySelector('.add-soft-skill');
    addButtonSoft.parentNode.insertBefore(formGroup, addButtonSoft);
}

// Gestionnaire d'événement pour le bouton de suppression d'un champ soft_skill
document.querySelectorAll('.delete-soft-skill').forEach(function (button) {
    button.addEventListener('click', function () {
        removeSoftSkill(button);
    });
});

// Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ soft_skill
const addButtonSoft = document.querySelector('.add-soft-skill');
if (addButtonSoft) {
    addButtonSoft.addEventListener('click', function () {
        addSoftSkill();
    })
}

//-----------------------------------------------------------------------------
//----------------------------HARD SKILLS---------------------------------
//-----------------------------------------------------------------------------


// Fonction pour supprimer un champ hard_skill
function removeHardSkill(button) {
    button.closest('.input-group').remove();
}

// Fonction pour ajouter un nouveau champ hard_skill
function addHardSkill() {
    // Récupération du prototype et incrémentation du compteur
    const prototype = document.querySelector('#cv_hard_skills').dataset.prototype;
    const index = document.querySelectorAll('.input-group').length;
    // Création du nouveau champ hard_skill
    const newForm = prototype.replace(/__name__/g, index);
    // Ajout du nouveau champ hard_skill au formulaire
    const formGroup = document.createElement('div');
    formGroup.classList.add('input-group', 'mb-3');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-end';
    formGroup.innerHTML = newForm;
    const button = document.createElement('button');
    button.type = 'button';
    button.classList.add('btn', 'btn-danger', 'delete-hard-skill');
    button.innerHTML = '<i class="fa fa-square-minus"></i>';
    button.style.width = 'fit-content';
    button.style.height = 'fit-content';
    button.addEventListener('click', function () {
        removeHardSkill(button);
    });
    formGroup.appendChild(button);
    const addButtonHard = document.querySelector('.add-hard-skill');
    addButtonHard.parentNode.insertBefore(formGroup, addButtonHard);
}

// Gestionnaire d'événement pour le bouton de suppression d'un champ hard_skill
document.querySelectorAll('.delete-hard-skill').forEach(function (button) {
    button.addEventListener('click', function () {
        removeHardSkill(button);
    });
});

// Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ hard_skill
const addButtonHard = document.querySelector('.add-hard-skill');
if (addButtonHard) {
    addButtonHard.addEventListener('click', function () {
        addHardSkill();
    })
}

//-----------------------------------------------------------------------------
//----------------------------LANGUES---------------------------------
//-----------------------------------------------------------------------------


// Fonction pour supprimer un champ langue
function removeLangue(button) {
    button.closest('.input-group').remove();
}

// Fonction pour ajouter un nouveau champ langue
function addLangue() {
    // Récupération du prototype et incrémentation du compteur
    const prototype = document.querySelector('#cv_langues').dataset.prototype;
    const index = document.querySelectorAll('.input-group').length;
    // Création du nouveau champ langue
    const newForm = prototype.replace(/__name__/g, index);
    // Ajout du nouveau champ langue au formulaire
    const formGroup = document.createElement('div');
    formGroup.classList.add('input-group', 'mb-3');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-end';
    formGroup.innerHTML = newForm;
    const button = document.createElement('button');
    button.type = 'button';
    button.classList.add('btn', 'btn-danger', 'delete-langue');
    button.innerHTML = '<i class="fa fa-square-minus"></i>';
    button.style.width = 'fit-content';
    button.style.height = 'fit-content';
    button.addEventListener('click', function () {
        removeLangue(button);
    });
    formGroup.appendChild(button);
    const addButtonLangue = document.querySelector('.add-langue');
    addButtonLangue.parentNode.insertBefore(formGroup, addButtonLangue);
}

// Gestionnaire d'événement pour le bouton de suppression d'un champ langue
document.querySelectorAll('.delete-langue').forEach(function (button) {
    button.addEventListener('click', function () {
        removeLangue(button);
    });
});

// Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ langue
const addButtonLangue = document.querySelector('.add-langue');
if (addButtonLangue) {
    addButtonLangue.addEventListener('click', function () {
        addLangue();
    })
}

//-----------------------------------------------------------------------------
//----------------------------RESEAUX---------------------------------
//-----------------------------------------------------------------------------


// Fonction pour supprimer un champ reseau
function removeReseau(button) {
    button.closest('.input-group').remove();
}

// Fonction pour ajouter un nouveau champ reseau
function addReseau() {
    // Récupération du prototype et incrémentation du compteur
    const prototype = document.querySelector('#cv_reseaux').dataset.prototype;
    const index = document.querySelectorAll('.input-group').length;
    // Création du nouveau champ reseau
    const newForm = prototype.replace(/__name__/g, index);
    // Ajout du nouveau champ reseau au formulaire
    const formGroup = document.createElement('div');
    formGroup.classList.add('input-group', 'mb-3');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-end';
    formGroup.innerHTML = newForm;
    const button = document.createElement('button');
    button.type = 'button';
    button.classList.add('btn', 'btn-danger', 'delete-reseau');
    button.innerHTML = '<i class="fa fa-square-minus"></i>';
    button.style.width = 'fit-content';
    button.style.height = 'fit-content';
    button.addEventListener('click', function () {
        removeReseau(button);
    });
    formGroup.appendChild(button);
    const addButtonReseau = document.querySelector('.add-reseau');
    addButtonReseau.parentNode.insertBefore(formGroup, addButtonReseau);
}

// Gestionnaire d'événement pour le bouton de suppression d'un champ reseau
document.querySelectorAll('.delete-reseau').forEach(function (button) {
    button.addEventListener('click', function () {
        removeReseau(button);
    });
});

// Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ reseau
const addButtonReseau = document.querySelector('.add-reseau');
if (addButtonReseau) {
    addButtonReseau.addEventListener('click', function () {
        addReseau();
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
    formGroup.classList.add('input-group', 'mb-3');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-end';
    formGroup.innerHTML = '<input type="text" id="a" class="form-control">';
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
    button.closest('.input-group').remove();
}
