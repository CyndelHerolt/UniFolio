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