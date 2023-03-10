//TODO: trouver pourquoi le btn d'ajout ne fonctionne pas lors de l'edition d'un cv
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
    var prototype = document.querySelector('#cv_soft_skills').dataset.prototype;
    var index = document.querySelectorAll('.input-group').length;
    // Création du nouveau champ soft_skill
    var newForm = prototype.replace(/__name__/g, index);
    // Ajout du nouveau champ soft_skill au formulaire
    var formGroup = document.createElement('div');
    formGroup.classList.add('input-group', 'mb-3');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-end';
    formGroup.innerHTML = newForm;
    var button = document.createElement('button');
    button.type = 'button';
    button.classList.add('btn', 'btn-danger', 'delete-soft-skill');
    button.innerHTML = '<i class="fa fa-square-minus"></i>';
    button.style.width = 'fit-content';
    button.style.height = 'fit-content';
    button.addEventListener('click', function () {
    removeSoftSkill(button);
});
    formGroup.appendChild(button);
    var addButtonSoft = document.querySelector('.add-soft-skill');
    addButtonSoft.parentNode.insertBefore(formGroup, addButtonSoft);
}

    // Gestionnaire d'événement pour le bouton de suppression d'un champ soft_skill
    document.querySelectorAll('.delete-soft-skill').forEach(function (button) {
    button.addEventListener('click', function () {
        removeSoftSkill(button);
    });
});

    // Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ soft_skill
    var addButtonSoft = document.querySelector('.add-soft-skill');
    addButtonSoft.addEventListener('click', function () {
    addSoftSkill();
});

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
    var prototype = document.querySelector('#cv_hard_skills').dataset.prototype;
    var index = document.querySelectorAll('.input-group').length;
    // Création du nouveau champ hard_skill
    var newForm = prototype.replace(/__name__/g, index);
    // Ajout du nouveau champ hard_skill au formulaire
    var formGroup = document.createElement('div');
    formGroup.classList.add('input-group', 'mb-3');
    formGroup.style.display = 'flex';
    formGroup.style.alignItems = 'flex-end';
    formGroup.innerHTML = newForm;
    var button = document.createElement('button');
    button.type = 'button';
    button.classList.add('btn', 'btn-danger', 'delete-hard-skill');
    button.innerHTML = '<i class="fa fa-square-minus"></i>';
    button.style.width = 'fit-content';
    button.style.height = 'fit-content';
    button.addEventListener('click', function () {
    removeHardSkill(button);
});
    formGroup.appendChild(button);
    var addButtonHard = document.querySelector('.add-hard-skill');
    addButtonHard.parentNode.insertBefore(formGroup, addButtonHard);
}

    // Gestionnaire d'événement pour le bouton de suppression d'un champ hard_skill
    document.querySelectorAll('.delete-hard-skill').forEach(function (button) {
    button.addEventListener('click', function () {
        removeHardSkill(button);
    });
});

    // Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ hard_skill
    var addButtonHard = document.querySelector('.add-hard-skill');
    addButtonHard.addEventListener('click', function () {
    addHardSkill();
});
