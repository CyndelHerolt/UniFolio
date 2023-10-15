import {Controller} from '@hotwired/stimulus';
import {createAndShow} from "../js/_toast";
import {
    addSoftSkill,
    removeSoftSkill,
    addHardSkill,
    removeHardSkill,
    addLangue,
    removeLangue,
    addReseau,
    removeReseau,
    addExperience,
    addExperienceActivite,
    removeExperience,
    removeExperienceActivite,
    addFormation,
    addFormationActivite,
    removeFormation,
    removeFormationActivite
} from "../js/_cv";
import {addLien} from "../js/_trace";

export default class extends Controller {
    static targets = ['navTabs', 'stepZone', 'cv', 'zone', 'cvZone']

    static values = {
        url: String,
        urlSave: String,
    }

    connect() {
        // console.log('hello');
    }

    async selectedCv(event) {
        // console.log('hello');
        const _value = event.currentTarget.value
        // récupérer data-page-id="{{ page.id }}" dans l'option
        const cvId = event.target.options[event.target.selectedIndex].dataset.cvId;

        const params = new URLSearchParams({
            step: 'selectedCv',
            cv: _value,
        })

        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        this.stepZoneTarget.innerHTML = await response.text()
        // Remettre la sélection sur l'option "Choisir..."
        event.target.selectedIndex = 0;
    }

    async deleteCv(event) {
        const _value = event.currentTarget.value

        const params = new URLSearchParams({
            step: 'deleteCv',
            cv: _value,
        })
        if (confirm('Voulez-vous vraiment retirer ce cv ?')) {
            const response = await fetch(`${this.urlValue}?${params.toString()}`)
            this.stepZoneTarget.innerHTML = await response.text()
        }
    }

    async editCv(event) {
        const _value = event.currentTarget.value

        const params = new URLSearchParams({
            step: 'editCv',
            cv: _value,
        })

        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        this.stepZoneTarget.innerHTML = await response.text()

        document.querySelectorAll('.soft_skills').forEach((event) => {
            // event = .soft_skills (chaque bloc soft_skills)
            // console.log(event)
            // ajouter la classe 'soft_skills' à chaque élément parent de .soft_skills
            event.parentNode.classList.add('soft_skills_div')
            event.parentNode.classList.add('input-group', 'mb-3', 'soft_skills_div')
            event.style.display = 'flex';
            event.style.alignItems = 'flex-end';
            // pour chaque bloc existant, on ajoute les boutons pour manipuler le form
            event.parentNode.innerHTML += (
                '<button type="button" class="btn btn-danger delete-soft-skill">' +
                '<i class="fa-solid fa-square-minus"></i>' +
                '</button>'
            )
        })

        const addSoftSkillButton = document.querySelector('.add-soft-skill');
        if (addSoftSkillButton) {
            addSoftSkillButton.addEventListener('click', function () {
                addSoftSkill()
            })
        }

        document.querySelectorAll('.hard_skills').forEach((event) => {
            // event = .hard_skills (chaque bloc hard_skills)
            // console.log(event)
            // ajouter la classe 'hard_skills' à chaque élément parent de .hard_skills
            event.parentNode.classList.add('input-group', 'mb-3', 'hard_skills_div')
            event.style.display = 'flex';
            event.style.alignItems = 'flex-end';
            // pour chaque bloc existant, on ajoute les boutons pour manipuler le form
            event.parentNode.innerHTML += (
                '<button type="button" class="btn btn-danger delete-hard-skill">' +
                '<i class="fa-solid fa-square-minus"></i>' +
                '</button>'
            )
        })

        const addHardSkillButton = document.querySelector('.add-hard-skill');
        if (addHardSkillButton) {
            addHardSkillButton.addEventListener('click', function () {
                addHardSkill()
            })
        }

        document.querySelectorAll('.langues').forEach((event) => {
            // event = .langues (chaque bloc langues)
            // console.log(event)
            // ajouter la classe 'langues' à chaque élément parent de .langues
            event.parentNode.classList.add('input-group', 'mb-3', 'langues_div')
            event.style.display = 'flex';
            event.style.alignItems = 'flex-end';
            // pour chaque bloc existant, on ajoute les boutons pour manipuler le form
            event.parentNode.innerHTML += (
                '<button type="button" class="btn btn-danger delete-langue">' +
                '<i class="fa-solid fa-square-minus"></i>' +
                '</button>'
            )
        })

        const addLangueButton = document.querySelector('.add-langue');
        if (addLangueButton) {
            addLangueButton.addEventListener('click', function () {
                addLangue()
            })
        }

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

        const addReseauButton = document.querySelector('.add-reseau');
        if (addReseauButton) {
            addReseauButton.addEventListener('click', function () {
                addReseau()
            })
        }

        document.querySelectorAll('.experience').forEach((event) => {
            // event = .experience (chaque bloc experience)
            const existingActivites = document.querySelectorAll('.experience-activite');
            // pour chaque existingActivites
            existingActivites.forEach((existingActivite) => {
                // récupérer la div .form-group la plus proche
                const existingActivitesDiv = existingActivite.closest('.form-group');
                // ajouter la classe 'experience-activite' à chaque existingActivitesDiv
                existingActivitesDiv.classList.add('experience-activite')
                existingActivitesDiv.style.display = 'flex';
                existingActivitesDiv.style.alignItems = 'flex-start';
                existingActivitesDiv.style.margin = '15px 0';
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

        const addExperienceButton = document.querySelector('.add-experience');
        if (addExperienceButton) {
            addExperienceButton.addEventListener('click', function (event) {
                addExperience(event.target)
            })
        }

        const addExperienceActiviteButton = document.querySelector('.add-experience-activite');
        if (addExperienceActiviteButton) {
            addExperienceActiviteButton.addEventListener('click', function (event) {
                // récupérer le bouton cliqué
                addExperienceActivite(event.target)
            })
        }

        document.querySelectorAll('.formation').forEach((event) => {
            // event = .experience (chaque bloc experience)
            const existingActivites = document.querySelectorAll('.formation-activite');
            // pour chaque existingActivites
            existingActivites.forEach((existingActivite) => {
                // récupérer la div .form-group la plus proche
                const existingActivitesDiv = existingActivite.closest('.form-group');
                // ajouter la classe 'experience-activite' à chaque existingActivitesDiv
                existingActivitesDiv.classList.add('formation-activite')
                existingActivitesDiv.style.display = 'flex';
                existingActivitesDiv.style.alignItems = 'flex-start';
                existingActivitesDiv.style.margin = '15px 0';
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

        const addFormationButton = document.querySelector('.add-formation');
        if (addFormationButton) {
            addFormationButton.addEventListener('click', function (event) {
                addFormation(event.target)
            })
        }

        const addFormationActiviteButton = document.querySelector('.add-formation-activite');
        if (addFormationActiviteButton) {
            addFormationActiviteButton.addEventListener('click', function (event) {
                addFormationActivite(event.target)
            })
        }

        // Gestionnaire d'événement pour le bouton de suppression d'un champ
        document.querySelectorAll('.delete-soft-skill').forEach(function (button) {
            button.addEventListener('click', function () {
                removeSoftSkill(button);
            });
        });

        document.querySelectorAll('.delete-hard-skill').forEach(function (button) {
            button.addEventListener('click', function () {
                removeHardSkill(button);
            });
        });

        document.querySelectorAll('.delete-langue').forEach(function (button) {
            button.addEventListener('click', function () {
                removeLangue(button);
            });
        });

        document.querySelectorAll('.delete-reseau').forEach(function (button) {
            button.addEventListener('click', function () {
                removeReseau(button);
            });
        });

        document.querySelectorAll('.delete-experience').forEach(function (button) {
            button.addEventListener('click', function () {
                removeExperience(button);
            });
        });

        document.querySelectorAll('.delete-experience-activite').forEach(function (button) {
            button.addEventListener('click', function () {
                removeExperienceActivite(button);
            });
        });

        document.querySelectorAll('.delete-formation').forEach(function (button) {
            button.addEventListener('click', function () {
                removeFormation(button);
            });
        });

        document.querySelectorAll('.delete-formation-activite').forEach(function (button) {
            button.addEventListener('click', function () {
                removeFormationActivite(button);
            });
        });
    }

    async saveCv(event) {
        const _value = event.currentTarget.value
        const formData = new FormData(document.getElementById('formCv'));

        const params = new URLSearchParams({
            step: 'saveCv',
            cv: _value,
        });

        // Envoyer les données du formulaire via une requête POST
        const response = await fetch(`${this.urlValue}?${params.toString()}`, {
            method: 'POST',
            body: formData
        })
            .then(async fetchResponse => {
            console.log(response)

                // Remove existing error messages
                document.querySelectorAll('.invalid-feedback').forEach((element) => {
                    element.remove();
                });

                // Remove "is-invalid" class from fields
                document.querySelectorAll('.is-invalid').forEach((element) => {
                    element.classList.remove('is-invalid');
                });

                const contentType = fetchResponse.headers.get("content-type");
                console.log(contentType);

                if (contentType && contentType.includes('application/json')) {
                    const jsonResponse = await fetchResponse.json();

                    if (fetchResponse.status === 500 && jsonResponse.errors && jsonResponse.errors.length > 0) {
                        // Loop through each error
                        jsonResponse.errors.forEach((error) => {
                            // Create an error message element
                            const errorElement = document.createElement('div');
                            errorElement.innerHTML = error.message;
                            errorElement.classList.add('invalid-feedback');

                            // Find the form field
                            let field = document.querySelector(`[name="page[${error.field}]"]`);

                            // Append the error message to the form field
                            if (field) {
                                field.parentNode.insertBefore(errorElement, field.nextSibling);

                                // Add "is-invalid" class to the form field
                                field.classList.add("is-invalid");
                            }
                        });
                    }
                } else if (fetchResponse.status !== 500) {
                    this.stepZoneTarget.innerHTML = await fetchResponse.text();
                    createAndShow('success', 'Les modifications ont été enregistrées avec succès.')
                }
            })
    }
}
