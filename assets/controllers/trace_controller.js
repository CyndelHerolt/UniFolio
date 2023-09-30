import {Controller} from '@hotwired/stimulus';
import {addImage, addLien, addPdf, addVideo, removeImage, removeLien, removePdf, removeVideo} from "../js/_trace";
import {createAndShow} from "../js/_toast";

export default class extends Controller {
    static targets = ['stepZone']

    static values = {
        url: String,
        urlSave: String,
    }

    async up(event) {
        const _value = event.currentTarget.value
        const page = event.currentTarget.dataset.page

        const params = new URLSearchParams({
            step: 'up',
            trace: _value,
            page: page,
        })
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        this.stepZoneTarget.innerHTML = await response.text()
    }

    async down(event) {
        const _value = event.currentTarget.value
        const page = event.currentTarget.dataset.page

        const params = new URLSearchParams({
            step: 'down',
            trace: _value,
            page: page,
        })
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        this.stepZoneTarget.innerHTML = await response.text()
    }

    initializeTinyMCEForId(id) {
        tinymce.init({
            skin: "oxide",
            content_css: "default",
            plugins: 'link image code',
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | code',
            menubar: 'edit view format table tools help'
        });
    }

    async editTrace(event) {
        const _value = event.currentTarget.value
        const type = event.currentTarget.dataset.type
        const page = event.currentTarget.dataset.page

        const params = new URLSearchParams({
            step: 'editTrace',
            trace: _value,
            type: type,
            page: page,
        })
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        //remplacer le contenu de la zone de trace défini dans page_controller.js par le contenu de la réponse
        this.stepZoneTarget.innerHTML = await response.text()

        if (document.getElementById('trace_type_image_description')) {
            this.initializeTinyMCEForId('trace_type_image_description');
        }
        if (document.getElementById('trace_type_lien_description')) {
            this.initializeTinyMCEForId('trace_type_lien_description');
        }
        if (document.getElementById('trace_type_pdf_description')) {
            this.initializeTinyMCEForId('trace_type_pdf_description');
        }
        if (document.getElementById('trace_type_video_description')) {
            this.initializeTinyMCEForId('trace_type_video_description');
        }

        // Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ contenu
        const addButtonImage = document.querySelector('.add-image');
        if (addButtonImage) {
            addButtonImage.addEventListener('click', function () {
                addImage()
            })
        }

        const addButtonPdf = document.querySelector('.add-pdf');
        if (addButtonPdf) {
            addButtonPdf.addEventListener('click', function () {
                addPdf()
            })
        }

        const addButtonLien = document.querySelector('.add-lien');
        if (addButtonLien) {
            addButtonLien.addEventListener('click', function () {
                addLien()
            })
        }

        const addButtonVideo = document.querySelector('.add-video');
        if (addButtonVideo) {
            addButtonVideo.addEventListener('click', function () {
                addVideo()
            })
        }

        // Gestionnaire d'événement pour le bouton de suppression d'un champ contenu
        document.querySelectorAll('.delete-image').forEach(function (button) {
            button.addEventListener('click', function () {
                removeImage(button);
            });
        });

        document.querySelectorAll('.delete-pdf').forEach(function (button) {
            button.addEventListener('click', function () {
                removePdf(button);
            });
        });

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

        document.querySelectorAll('.video_trace').forEach(event => {
            event.parentNode.classList.add('video_trace_div')
            // event.parentNode.classList.add('input-group', 'mb-3');
            // event.style.display = 'flex';
            // event.style.alignItems = 'flex-end';
            // pour chaque bloc existant, on ajoute les boutons pour manipuler le form
            event.parentNode.innerHTML += (
                '<button type="button" class="btn btn-danger delete-video">' +
                '<i class="fa-solid fa-square-minus"></i>' +
                '</button>'
            )
        })

        document.querySelectorAll('.delete-video').forEach(function (button) {
            button.addEventListener('click', function () {
                removeVideo(button);
            });
        });
    }


    async formTrace(event) {
        const _value = event.currentTarget.value
        const type = event.currentTarget.dataset.type

        const params = new URLSearchParams({
            step: 'formTrace',
            trace: _value,
            type: type,
        })
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        //remplacer le contenu de la zone de trace définie dans page_controller.js par le contenu de la réponse
        this.stepZoneTarget.innerHTML = await response.text()

        if (document.getElementById('trace_type_image_description')) {
            this.initializeTinyMCEForId('trace_type_image_description');
        }
        if (document.getElementById('trace_type_lien_description')) {
            this.initializeTinyMCEForId('trace_type_lien_description');
        }
        if (document.getElementById('trace_type_pdf_description')) {
            this.initializeTinyMCEForId('trace_type_pdf_description');
        }
        if (document.getElementById('trace_type_video_description')) {
            this.initializeTinyMCEForId('trace_type_video_description');
        }

        // Gestionnaire d'événement pour le bouton d'ajout d'un nouveau champ contenu
        const addButtonImage = document.querySelector('.add-image');
        if (addButtonImage) {
            addButtonImage.addEventListener('click', function () {
                addImage()
            })
        }

        const addButtonPdf = document.querySelector('.add-pdf');
        if (addButtonPdf) {
            addButtonPdf.addEventListener('click', function () {
                addPdf()
            })
        }

        const addButtonLien = document.querySelector('.add-lien');
        if (addButtonLien) {
            addButtonLien.addEventListener('click', function () {
                addLien()
            })
        }

        const addButtonVideo = document.querySelector('.add-video');
        if (addButtonVideo) {
            addButtonVideo.addEventListener('click', function () {
                addVideo()
            })
        }
    }

    async deleteTrace(event) {
        const _value = event.currentTarget.value
        const page = event.currentTarget.dataset.page

        const params = new URLSearchParams({
            step: 'deleteTrace',
            trace: _value,
            page: page,
        })
        if (confirm('Voulez-vous vraiment supprimer cette trace ?')) {
            const response = await fetch(`${this.urlValue}?${params.toString()}`)
            this.stepZoneTarget.innerHTML = await response.text()
        }
    }

    async saveTrace(event) {

        for (var instance in tinymce.editors) {
            tinymce.editors[instance].save();
        }

        event.preventDefault()
        const _value = event.currentTarget.value
        const type = event.currentTarget.dataset.type

        const formData = await new FormData(document.getElementById('formTrace'));

        const params = new URLSearchParams({
            step: 'saveTrace',
            trace: _value,
            type: type,
        })

        // Envoyer les données du formulaire via une requête POST
        const response = await fetch(`${this.urlValue}?${params.toString()}`, {
            method: 'POST',
            body: formData,
        })
            .then(async fetchResponse => {

                // Remove existing error messages
                document.querySelectorAll('.invalid-feedback').forEach((element) => {
                    element.remove();
                });

                // Remove "is-invalid" class from fields
                document.querySelectorAll('.is-invalid').forEach((element) => {
                    element.classList.remove('is-invalid');
                });

                // Remove style from .competences
                document.querySelector('.competences').style.border = 'none';
                document.getElementById('zone-error').innerHTML = '';

                const contentType = fetchResponse.headers.get('content-type');

                if (contentType && contentType.includes('application/json')) {
                    const jsonResponse = await fetchResponse.json();

                    if (fetchResponse.status === 500 && jsonResponse.errors && jsonResponse.errors.length > 0) {
                        // Loop through each error
                        jsonResponse.errors.forEach((error) => {
                            // Create an error message element
                            const errorElement = document.createElement('div');
                            errorElement.innerHTML = error.message;
                            errorElement.classList.add('invalid-feedback');

                            let field

                            if (type === 'App\\Components\\Trace\\TypeTrace\\TraceTypeImage') {
                                // Find the form field
                                field = document.querySelector(`[name="trace_type_image[${error.field}]"]`);
                            } else if (type === 'App\\Components\\Trace\\TypeTrace\\TraceTypePdf') {
                                // Find the form field
                                field = document.querySelector(`[name="trace_type_pdf[${error.field}]"]`);
                            } else if (type === 'App\\Components\\Trace\\TypeTrace\\TraceTypeLien') {
                                // Find the form field
                                field = document.querySelector(`[name="trace_type_lien[${error.field}]"]`);
                            } else if (type === 'App\\Components\\Trace\\TypeTrace\\TraceTypeVideo') {
                                // Find the form field
                                field = document.querySelector(`[name="trace_type_video[${error.field}]"]`);
                            }

                            console.log(error.field)
                            if (error.field === 'contenu') {
                                // Find the error container
                                const errorContainer = document.querySelector('.contenu-error');
                                errorContainer.style.color = '#dc3545';
                                // Append the error message to the container
                                errorContainer.innerHTML = errorElement.innerHTML;
                            }

                            if (error.field === 'competences') {

                                // Find the error container
                                const errorContainer = document.getElementById('zone-error');
                                const formCompetences = document.querySelector('.competences');
                                errorContainer.style.color = '#dc3545';
                                formCompetences.style.border = '1px solid #dc3545';

                                // Append the error message to the container
                                errorContainer.innerHTML = errorElement.innerHTML;
                            }

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