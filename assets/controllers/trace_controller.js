import {Controller} from '@hotwired/stimulus';
import {addImage, addLien, addPdf, addVideo, removeImage, removeLien, removePdf, removeVideo} from "../js/_trace";

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

    async editTrace(event) {
        const _value = event.currentTarget.value
        const type = event.currentTarget.dataset.type

        const params = new URLSearchParams({
            step: 'editTrace',
            trace: _value,
            type: type,
        })
        console.log(params.toString());
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        console.log(response);
        //remplacer le contenu de la zone de trace défini dans page_controller.js par le contenu de la réponse
        this.stepZoneTarget.innerHTML = await response.text()


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
            // console.log(event)
            event.parentNode.classList.add('lien_trace_div')
            // event.parentNode.classList.add('input-group', 'mb-3');
            // event.style.display = 'flex';
            // event.style.alignItems = 'flex-end';
            // pour chaque bloc existant, on ajoute les boutons pour manipuler le form
            event.parentNode.innerHTML += (
                '<button type="button" class="btn btn-danger delete-lien">' +
                '<i class="fa-solid fa-square-minus"></i>' +
                '</button>'
            )
        })

        document.querySelectorAll('.delete-lien').forEach(function (button) {
            button.addEventListener('click', function () {
                removeLien(button);
            });
        });

        document.querySelectorAll('.video_trace').forEach(event => {
            // console.log(event)
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
        event.preventDefault()
        const _value = event.currentTarget.value
        const type = event.currentTarget.dataset.type

        const formData = await new FormData(document.getElementById('formTrace'));

        const params = new URLSearchParams({
            step: 'saveTrace',
            trace: _value,
            type: type,
        })

        console.log(formData.getAll('trace_type_pdf'));

        // Envoyer les données du formulaire via une requête POST
            const response = await fetch(`${this.urlValue}?${params.toString()}`, {
                method: 'POST',
                body: formData,
            })
                .then(async response => {
                    if (response.status === 500) {
                        const reponse = await response.json()
                    console.log(reponse);
                    } else {
                        this.stepZoneTarget.innerHTML = await response.text()
                    }
                })
    }
}