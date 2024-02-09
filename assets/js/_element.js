// Créer une instance de l'observateur lié à la fonction de callback
const observer = new MutationObserver(addEventListeners);

// Commencer à observer le document avec la configuration configurée
observer.observe(document.body, { childList: true, subtree: true });

// Fonctions de gestionnaire d'événements
function removeListeElementEvent() {
    removeListeElement(this);
}

function addListeElementEvent() {
    addListeElement();
}

function addEventListeners() {
    // Gestionnaire d'événement pour le bouton de suppression d'un champ liste_element
    document.querySelectorAll('.delete-liste-element').forEach(function (button) {
        if (button.hasListener) {
            button.removeEventListener('click', removeListeElementEvent);
        }
        button.addEventListener('click', removeListeElementEvent);
        button.hasListener = true;
    });

    // Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ liste_element
    const addButtonListe = document.querySelector('.add-liste-element');
    if (addButtonListe) {
        if (addButtonListe.hasListener) {
            addButtonListe.removeEventListener('click', addListeElementEvent);
        }
        addButtonListe.addEventListener('click', addListeElementEvent);
        addButtonListe.hasListener = true;
    }
}

//-----------------------------------------------------------------------------
//---------------------------------LISTE---------------------------------
//-----------------------------------------------------------------------------
console.log('element.js chargé');
document.querySelectorAll('.liste_element').forEach((event) => {
    // event = .liste_element (chaque bloc liste_element)
    // ajouter la classe 'liste_element' à chaque élément parent de .liste_element
    event.parentNode.classList.add('liste_element_div')
    event.parentNode.classList.add('input-group', 'mb-3', 'liste_element_div')
    event.style.display = 'flex';
    event.style.alignItems = 'flex-start';
    // pour chaque bloc existant, on ajoute les boutons pour manipuler le form
    event.parentNode.innerHTML += (
        '<button type="button" class="btn btn-danger delete-liste-element">' +
        '<i class="fa-solid fa-square-minus"></i>' +
        '</button>'
    )
})

// Fonction pour supprimer un champ liste_element
export function removeListeElement(button) {
    button.closest('.liste_element_div').remove();
}

// Fonction pour créer un élément de liste
function createListElement(content) {
    const listItem = document.createElement('li');
    listItem.innerHTML = content;
    listItem.classList.add('liste_element_div');
    listItem.classList.add('input-group', 'mb-3');
    listItem.style.display = 'flex';
    listItem.style.alignItems = 'flex-start';
    listItem.innerHTML += (
        '<button type="button" class="btn btn-danger delete-liste-element">' +
        '<i class="fa-solid fa-square-minus"></i>' +
        '</button>'
    );
    return listItem;
}

// Pour chaque élément existant, créez un élément de liste
document.querySelectorAll('.liste_element').forEach((event) => {
    const listItem = createListElement(event.innerHTML);
    event.replaceWith(listItem);
});

// Lors de l'ajout d'un nouvel élément, créez un élément de liste
export function addListeElement(event) {
    // Récupération du prototype
    const prototype = document.querySelector('#liste_contenu').dataset.prototype;
    // Récupération du nombre de champs liste_element
    const index = document.querySelectorAll('.liste_element').length;
    // Création du nouveau champ liste_element
    const newForm = prototype.replace(/__name__/g, index);
    // Création d'un nouvel élément de liste
    const listItem = createListElement(newForm);
    // Ajout du nouvel élément de liste au formulaire
    const ulElement = document.querySelector('.add-liste-element').parentNode.querySelector('ul');
    ulElement.appendChild(listItem);

    // Ajout d'un écouteur d'événements au bouton de suppression du nouvel élément
    const deleteButton = listItem.querySelector('.delete-liste-element');
    deleteButton.addEventListener('click', function () {
        removeListeElement(deleteButton);
    });
}

// Gestionnaire d'événement pour le bouton de suppression d'un champ liste_element
document.querySelectorAll('.delete-liste-element').forEach(function (button) {
    button.addEventListener('click', function () {
        removeListeElement(button);
    });
});

// Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ liste_element
const addButtonListe = document.querySelector('.add-liste-element');
console.log(addButtonListe);
if (addButtonListe) {
    addButtonListe.addEventListener('click', function () {
        addListeElement();
    })
}