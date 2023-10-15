//-----------------------------------------------------------------------------
//---------------------------------SOFT SKILLS---------------------------------
//-----------------------------------------------------------------------------

document.querySelectorAll('.soft_skills').forEach((event) => {
    // event = .soft_skills (chaque bloc soft_skills)
    // ajouter la classe 'soft_skills' à chaque élément parent de .soft_skills
    event.parentNode.classList.add('soft_skills_div')
    event.parentNode.classList.add('input-group', 'mb-3', 'soft_skills_div')
    event.style.display = 'flex';
    event.style.alignItems = 'flex-start';
    // pour chaque bloc existant, on ajoute les boutons pour manipuler le form
    event.parentNode.innerHTML += (
        '<button type="button" class="btn btn-danger delete-soft-skill">' +
        '<i class="fa-solid fa-square-minus"></i>' +
        '</button>'
    )
})

// Fonction pour supprimer un champ soft_skill
export function removeSoftSkill(button) {
    button.closest('.soft_skills_div').remove();
}

// Fonction pour ajouter un nouveau champ soft_skill
export function addSoftSkill(event) {
    // Récupération du prototype
    const prototype = document.querySelector('#cv_soft_skills').dataset.prototype;
    // Récupération du nombre de champs soft_skill
    const index = document.querySelectorAll('.soft_skills').length;
    // Création du nouveau champ soft_skill
    const newForm = prototype.replace(/__name__/g, index);
    // Ajout du nouveau champ soft_skill au formulaire
    const formGroup = document.createElement('div');

    // Injection du prototype et des btns ds le nouveau bloc
    formGroup.classList.add('input-group', 'mb-3', 'soft_skills_div');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-start';
    formGroup.innerHTML = newForm;
    formGroup.innerHTML += (
        '<button type="button" class="btn btn-danger delete-soft-skill">' +
        '<i class="fa-solid fa-square-minus"></i>' +
        '</button>'
    )


    const button = formGroup.querySelector('.delete-soft-skill')
    button.addEventListener('click', function () {
        removeSoftSkill(this);
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

document.querySelectorAll('.hard_skills').forEach((event) => {
    // event = .hard_skills (chaque bloc hard_skills)
    // ajouter la classe 'hard_skills' à chaque élément parent de .hard_skills
    event.parentNode.classList.add('input-group', 'mb-3', 'hard_skills_div')
    event.style.display = 'flex';
    event.style.alignItems = 'flex-start';
    // pour chaque bloc existant, on ajoute les boutons pour manipuler le form
    event.parentNode.innerHTML += (
        '<button type="button" class="btn btn-danger delete-hard-skill">' +
        '<i class="fa-solid fa-square-minus"></i>' +
        '</button>'
    )
})

// Fonction pour supprimer un champ hard_skill
export function removeHardSkill(button) {
    button.closest('.hard_skills_div').remove();
}

// Fonction pour ajouter un nouveau champ hard_skill
export function addHardSkill(event) {
    // Récupération du prototype
    const prototype = document.querySelector('#cv_hard_skills').dataset.prototype;
    // Récupération du nombre de champs hard_skill
    const index = document.querySelectorAll('.hard_skills').length;
    // Création du nouveau champ hard_skill
    const newForm = prototype.replace(/__name__/g, index);
    // Ajout du nouveau champ hard_skill au formulaire
    const formGroup = document.createElement('div');

    // Injection du prototype et des btns ds le nouveau bloc
    formGroup.classList.add('input-group', 'mb-3', 'hard_skills_div');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-start';
    formGroup.innerHTML = newForm;
    formGroup.innerHTML += (
        '<button type="button" class="btn btn-danger delete-hard-skill">' +
        '<i class="fa-solid fa-square-minus"></i>' +
        '</button>'
    )


    const button = formGroup.querySelector('.delete-hard-skill')
    button.addEventListener('click', function () {
        removeHardSkill(this);
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


document.querySelectorAll('.langues').forEach((event) => {
    // event = .langues (chaque bloc langues)
    // ajouter la classe 'langues' à chaque élément parent de .langues
    event.parentNode.classList.add('input-group', 'mb-3', 'langues_div')
    event.style.display = 'flex';
    event.style.alignItems = 'flex-start';
    // pour chaque bloc existant, on ajoute les boutons pour manipuler le form
    event.parentNode.innerHTML += (
        '<button type="button" class="btn btn-danger delete-langue">' +
        '<i class="fa-solid fa-square-minus"></i>' +
        '</button>'
    )
})

// Fonction pour supprimer un champ soft_skill
export function removeLangue(button) {
    button.closest('.langues_div').remove();
}

// Fonction pour ajouter un nouveau champ soft_skill
export function addLangue(event) {
    // Récupération du prototype
    const prototype = document.querySelector('#cv_langues').dataset.prototype;
    // Récupération du nombre de champs soft_skill
    const index = document.querySelectorAll('.langues').length;
    // Création du nouveau champ soft_skill
    const newForm = prototype.replace(/__name__/g, index);
    // Ajout du nouveau champ soft_skill au formulaire
    const formGroup = document.createElement('div');

    // Injection du prototype et des btns ds le nouveau bloc
    formGroup.classList.add('input-group', 'mb-3', 'langues_div');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-start';
    formGroup.innerHTML = newForm;
    formGroup.innerHTML += (
        '<button type="button" class="btn btn-danger delete-langue">' +
        '<i class="fa-solid fa-square-minus"></i>' +
        '</button>'
    )


    const button = formGroup.querySelector('.delete-langue')
    button.addEventListener('click', function () {
        removeLangue(this);
    });
    formGroup.appendChild(button);
    const addButtonLangue = document.querySelector('.add-langue');
    addButtonLangue.parentNode.insertBefore(formGroup, addButtonLangue);
}

// Gestionnaire d'événement pour le bouton de suppression d'un champ soft_skill
document.querySelectorAll('.delete-langue').forEach(function (button) {
    button.addEventListener('click', function () {
        removeLangue(button);
    });
});

// Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ soft_skill
const addButtonLangue = document.querySelector('.add-langue');
if (addButtonLangue) {
    addButtonLangue.addEventListener('click', function () {
        addLangue();
    })
}

//-----------------------------------------------------------------------------
//----------------------------RESEAUX---------------------------------
//-----------------------------------------------------------------------------


document.querySelectorAll('.reseaux').forEach((event) => {
    // event = .reseaux (chaque bloc reseaux)
    // ajouter la classe 'reseaux' à chaque élément parent de .reseaux
    event.parentNode.classList.add('input-group', 'mb-3', 'reseaux_div')
    event.style.display = 'flex';
    event.style.alignItems = 'flex-start';
    // pour chaque bloc existant, on ajoute les boutons pour manipuler le form
    event.parentNode.innerHTML += (
        '<button type="button" class="btn btn-danger delete-reseau">' +
        '<i class="fa-solid fa-square-minus"></i>' +
        '</button>'
    )
})

// Fonction pour supprimer un champ soft_skill
export function removeReseau(button) {
    button.closest('.reseaux_div').remove();
}

// Fonction pour ajouter un nouveau champ soft_skill
export function addReseau(event) {
    // Récupération du prototype
    const prototype = document.querySelector('#cv_reseaux').dataset.prototype;
    // Récupération du nombre de champs soft_skill
    const index = document.querySelectorAll('.reseaux').length;
    // Création du nouveau champ soft_skill
    const newForm = prototype.replace(/__name__/g, index);
    // Ajout du nouveau champ soft_skill au formulaire
    const formGroup = document.createElement('div');

    // Injection du prototype et des btns ds le nouveau bloc
    formGroup.classList.add('input-group', 'mb-3', 'reseaux_div');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-start';
    formGroup.innerHTML = newForm;
    formGroup.innerHTML += (
        '<button type="button" class="btn btn-danger delete-reseau">' +
        '<i class="fa-solid fa-square-minus"></i>' +
        '</button>'
    )


    const button = formGroup.querySelector('.delete-reseau')
    button.addEventListener('click', function () {
        removeReseau(this);
    });
    formGroup.appendChild(button);
    const addButtonReseau = document.querySelector('.add-reseau');
    addButtonReseau.parentNode.insertBefore(formGroup, addButtonReseau);
}

// Gestionnaire d'événement pour le bouton de suppression d'un champ soft_skill
document.querySelectorAll('.delete-reseau').forEach(function (button) {
    button.addEventListener('click', function () {
        removeReseau(button);
    });
});

// Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ soft_skill
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
    const existingActivites = document.querySelectorAll('.experience-activite');
    // pour chaque existingActivites
    existingActivites.forEach((existingActivite) => {
        // récupérer la div .form-group la plus proche
        const existingActivitesDiv = existingActivite.closest('.form-group');
        // ajouter la classe 'experience-activite' à chaque existingActivitesDiv
        existingActivitesDiv.classList.add('experience-activite')
        existingActivitesDiv.innerHTML += (
            '<button type="button" class="btn btn-danger delete-experience-activite">' +
            '<i class="fa-solid fa-square-minus"></i>' + '</button>'
        )
    })
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
export function addExperience(event) {
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
        '<div><button type="button" class="btn btn-primary add-experience-activite" data-exp="' + index + '">' +
        'Ajouter une activité <i class="fa fa-square-plus"></i>' +
        '</button></div>' +
        '<br>' +
        '<button type="button" class="btn btn-danger delete-experience">' +
        'Supprimer l\'expérience <i class="fa-solid fa-square-minus"></i>' +
        '</button>'
    )

    // Injection du nouveau bloc experience
    event.parentNode.insertBefore(formGroup, event);
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
export function removeExperience(event) {
    event.closest('.experience').remove();
}

// Gestionnaire d'événement pour le bouton de suppression d'un champ experience
document.querySelectorAll('.delete-experience').forEach(function (event) {
    event.addEventListener('click', function () {
        removeExperience(event);
    });
});

// Fonction pour ajouter un nouveau champ experience activite
export function addExperienceActivite(event) {
    const formGroup = document.createElement('div');
    formGroup.classList.add('experience-activite');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-start';
    formGroup.innerHTML += '<label for="activite" class="form-label">Activité</label>';

    let index = document.querySelectorAll('.experience-activite').length;
    const experienceElement = event.closest('.experience');
    if (experienceElement === null) {
        // handle error, for example:
        console.error('Could not find parent element with class "experience"');
        return;
    }
    let indexExp = experienceElement.id.split('_')[2];

    formGroup.innerHTML += '<input type="text" name="cv[experience][' + indexExp + '][activite][' + index + ']" id="' + indexExp + '_activite_' + index + '" class="form-control activite">';

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
export function removeExperienceActivite(button) {
    button.closest('div').remove();
}


//-----------------------------------------------------------------------------
//----------------------------FORMATIONS(--------------------------------------
//-----------------------------------------------------------------------------

document.querySelectorAll('.formation').forEach((event) => {
    // event = .experience (chaque bloc experience)
    const existingActivites = document.querySelectorAll('.formation-activite');
    // pour chaque existingActivites
    existingActivites.forEach((existingActivite) => {
        // récupérer la div .form-group la plus proche
        const existingActivitesDiv = existingActivite.closest('.form-group');
        // ajouter la classe 'experience-activite' à chaque existingActivitesDiv
        existingActivitesDiv.classList.add('formation-activite')
        existingActivitesDiv.innerHTML += (
            '<button type="button" class="btn btn-danger delete-formation-activite">' +
            '<i class="fa-solid fa-square-minus"></i>' + '</button>'
        )
    })
    // pour chaque bloc formation existant, on ajoute les boutons pour manipuler le form
    event.innerHTML += (
        '<div><button type="button" class="btn btn-primary add-formation-activite">' +
        'Ajouter une activité <i class="fa fa-square-plus"></i>' +
        '</button></div>' +
        '<br>' +
        '<button type="button" class="btn btn-danger delete-formation">' +
        'Supprimer la formation <i class="fa-solid fa-square-minus"></i>' +
        '</button>'
    )
})

// Fonction pour ajouter un nouveau champ formation
export function addFormation(event) {
    // Récupération du prototype
    const prototype = document.querySelector('#cv_formation').dataset.prototype;
    // Récupération du nombre d'expériences existantes
    const index = document.querySelectorAll('.formation').length;
    // Création du nouveau form formation
    const newForm = prototype.replace(/__name__/g, index);
    // Création d'un bloc formation pour le nouveau form
    const formGroup = document.createElement('div');

    // Injection du prototype et des boutons dans le nouveau bloc formation
    formGroup.innerHTML = newForm;
    formGroup.querySelector('.formation').innerHTML += (
        '<div><button type="button" class="btn btn-primary add-formation-activite">' +
        'Ajouter une activité <i class="fa fa-square-plus"></i>' +
        '</button></div>' +
        '<br>' +
        '<button type="button" class="btn btn-danger delete-formation">' +
        'Supprimer l\'expérience <i class="fa-solid fa-square-minus"></i>' +
        '</button>'
    )

    // Injection du nouveau bloc formation
    event.parentNode.insertBefore(formGroup, event);
    // Attacher un gestionnaire d'événements au nouveau bouton "delete-formation"
    formGroup.querySelector('.delete-formation').addEventListener('click', function () {
        removeFormation(this);
    });
    // Attacher un gestionnaire d'événements au nouveau bouton "add-formation-activite"
    formGroup.querySelector('.add-formation-activite').addEventListener('click', function () {
        addFormationActivite(this);
    });
}

// Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ formation
const addButtonFormation = document.querySelector('.add-formation');
if (addButtonFormation) {
    addButtonFormation.addEventListener('click', function (event) {
        addFormation(event.target);
    })
}

// Fonction pour supprimer un champ formation
export function removeFormation(event) {
    event.closest('.formation').remove();
}

// Gestionnaire d'événement pour le bouton de suppression d'un champ formation
document.querySelectorAll('.delete-formation').forEach(function (event) {
    event.addEventListener('click', function () {
        removeFormation(event);
    });
});

// Fonction pour ajouter un nouveau champ formation activite
export function addFormationActivite(event) {
    const formGroup = document.createElement('div');
    formGroup.classList.add('form-activite');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-start';
    formGroup.innerHTML += '<label for="activite" class="form-label">Activité</label>';

    let index = document.querySelectorAll('.formation-activite').length;
    const formationElement = event.closest('.formation');
    if (formationElement === null) {
        // handle error, for example:
        console.error('Could not find parent element with class "formation"');
        return;
    }
    let indexF = formationElement.id.split('_')[2];

    formGroup.innerHTML += '<input type="text" name="cv[formation][' + indexF + '][activite][' + index + ']" id="' + indexF + '_activite_' + index + '" class="form-control activite">'

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
    event.parentNode.insertBefore(formGroup, event);
}

// Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ formation activite
document.querySelectorAll('.add-formation-activite').forEach(function (event) {
    event.addEventListener('click', function () {
        addFormationActivite(event);
    });
});

// Gestionnaire d'événement pour le bouton de suppression d'un champ activite
document.querySelectorAll('.delete-formation-activite').forEach(function (button) {
    button.addEventListener('click', function () {
        removeFormationActivite(button);
    });
});

// Fonction pour supprimer un champ formation
export function removeFormationActivite(button) {
    button.closest('div').remove();
}