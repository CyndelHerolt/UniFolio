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


// Fonction pour supprimer un champ experience
function removeExperience(button) {
    button.closest('.input-group').remove();
}

// Fonction pour ajouter un nouveau champ experience
function addExperience() {
    // Récupération du prototype et incrémentation du compteur
    const prototype = document.querySelector('#cv_experience').dataset.prototype;
    const index = document.querySelectorAll('.bloc_experience').length;
    console.log(index)
    // Création du nouveau champ reseau
    const newForm = prototype.replace(/__name__/g, index);
    // Ajout du nouveau champ reseau au formulaire
    const formGroup = document.createElement('div');
    formGroup.classList.add('input-group', 'mb-3', 'bloc_experience');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-end';
    formGroup.innerHTML = newForm;
    const button = document.createElement('button');
    button.type = 'button';
    button.classList.add('btn', 'btn-danger', 'delete-experience');
    button.innerHTML = '<i class="fa fa-square-minus"></i>';
    button.style.width = 'fit-content';
    button.style.height = 'fit-content';
    button.addEventListener('click', function () {
        removeExperience(button);
    });
    const buttonExpActivite = document.createElement('button');
    buttonExpActivite.type = 'button';
    buttonExpActivite.dataset.index = index;
    buttonExpActivite.classList.add('btn', 'btn-primary', 'add-experience-activite');
    buttonExpActivite.innerHTML = 'Ajouter une activité <i class="fa fa-square-plus"></i>';
    buttonExpActivite.style.width = 'fit-content';
    buttonExpActivite.style.height = 'fit-content';
    buttonExpActivite.addEventListener('click', function () {
        addExperienceActivite(buttonExpActivite);
    });
    formGroup.appendChild(button);
    const addButtonExperience = document.querySelector('.add-experience');
    addButtonExperience.parentNode.insertBefore(formGroup, addButtonExperience);
    formGroup.appendChild(buttonExpActivite);
    const addButtonExpActivite = document.querySelector('.add-experience-activite');
    addButtonExpActivite.parentNode.insertAfter(formGroup, addButtonExpActivite);
}

// Gestionnaire d'événement pour le bouton de suppression d'un champ reseau
document.querySelectorAll('.delete-experience').forEach(function (button) {
    button.addEventListener('click', function () {
        removeExperience(button);
    });
});

// Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ reseau
const addButtonExperience = document.querySelector('.add-experience');
if (addButtonExperience) {
    addButtonExperience.addEventListener('click', function () {
        addExperience();
    })
}


// -----------------------------------------------------------------------------
// ----------------------------EXP-ACTIVITES------------------------------------
// -----------------------------------------------------------------------------


// Fonction pour supprimer un champ formation
function removeExperienceActivite(button) {
    button.closest('.input-group').remove();
}

// Fonction pour ajouter un nouveau champ activite
function addExperienceActivite(event) {
    // Récupération du prototype et incrémentation du compteur
//     event = clic sur bouton ajouter activité
console.log(event)
    //const prototype = document.querySelector('#cv_experience_0_activite_' + event.dataset.index).dataset.prototype;
    // const prototype = document.querySelector('#cv_experience_0_activite').dataset.prototype;
    // const index = document.querySelectorAll('.input-group').length;
    // // Création du nouveau champ reseau
    // const newForm = prototype.replace(/__name__/g, index);
    // Ajout du nouveau champ activite au formulaire
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
    event.parentNode.insertBefore(formGroup, addButtonExperienceActivite);
}

// Gestionnaire d'événement pour le bouton de suppression d'un champ activite
document.querySelectorAll('.delete-experience-activite').forEach(function (button) {
    button.addEventListener('click', function () {
        removeExperienceActivite(button);
    });
});

// Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ activite
const addButtonExperienceActivite = document.querySelector('.add-experience-activite');
if (addButtonExperienceActivite) {
    addButtonExperienceActivite.addEventListener('click', function (event) {
        addExperienceActivite(event.target);
    })
}


//-----------------------------------------------------------------------------
//----------------------------FORMATIONS---------------------------------------
//-----------------------------------------------------------------------------


// Fonction pour supprimer un champ formation
function removeFormation(button) {
    button.closest('.input-group').remove();
}

// Fonction pour ajouter un nouveau champ reseau
function addFormation() {
    // Récupération du prototype et incrémentation du compteur
    const prototype = document.querySelector('#cv_formation').dataset.prototype;
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
    button.classList.add('btn', 'btn-danger', 'delete-formation');
    button.innerHTML = '<i class="fa fa-square-minus"></i>';
    button.style.width = 'fit-content';
    button.style.height = 'fit-content';
    button.addEventListener('click', function () {
        removeFormation(button);
    });
    formGroup.appendChild(button);
    const addButtonFormation = document.querySelector('.add-formation');
    addButtonFormation.parentNode.insertBefore(formGroup, addButtonFormation);
}

// Gestionnaire d'événement pour le bouton de suppression d'un champ reseau
document.querySelectorAll('.delete-formation').forEach(function (button) {
    button.addEventListener('click', function () {
        removeFormation(button);
    });
});

// Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ reseau
const addButtonFormation = document.querySelector('.add-formation');
if (addButtonFormation) {
    addButtonFormation.addEventListener('click', function () {
        addFormation();
    })
}

// -----------------------------------------------------------------------------
// ----------------------------FORMATION-ACTIVITES------------------------------------
// -----------------------------------------------------------------------------


// Fonction pour supprimer un champ formation
function removeFormationActivite(button) {
    button.closest('.input-group').remove();
}

// Fonction pour ajouter un nouveau champ activite
function addFormationActivite() {
    // Récupération du prototype et incrémentation du compteur
    const prototype = document.querySelector('#cv_formation_0_activite').dataset.prototype;
    const index = document.querySelectorAll('.input-group').length;
    // Création du nouveau champ reseau
    const newForm = prototype.replace(/__name__/g, index);
    // Ajout du nouveau champ activite au formulaire
    const formGroup = document.createElement('div');
    formGroup.classList.add('input-group', 'mb-3');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-end';
    formGroup.innerHTML = newForm;
    const button = document.createElement('button');
    button.type = 'button';
    button.classList.add('btn', 'btn-danger', 'delete-formation-activite');
    button.innerHTML = '<i class="fa fa-square-minus"></i>';
    button.style.width = 'fit-content';
    button.style.height = 'fit-content';
    button.addEventListener('click', function () {
        removeFormationActivite(button);
    });
    formGroup.appendChild(button);
    const addButtonFormationActivite = document.querySelector('.add-formation-activite');
    addButtonFormationActivite.parentNode.insertBefore(formGroup, addButtonFormationActivite);
}

// Gestionnaire d'événement pour le bouton de suppression d'un champ activite
document.querySelectorAll('.delete-formation-activite').forEach(function (button) {
    button.addEventListener('click', function () {
        removeFormationActivite(button);
    });
});

// Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ activite
const addButtonFormationActivite = document.querySelector('.add-formation-activite');
if (addButtonFormationActivite) {
    addButtonFormationActivite.addEventListener('click', function () {
        addFormationActivite();
    })
}
