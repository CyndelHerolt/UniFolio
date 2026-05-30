import {Controller} from '@hotwired/stimulus';
import {addImage, addLien, addPdf, addVideo, removeImage, removeLien, removePdf, removeVideo} from "../js/_trace";
import {createAndShow} from "../js/_toast";

export default class extends Controller {
    static targets = ['stepZone']

    static values = {
        url: String,
        urlSave: String,
    }

    connect() {
        this.element.addEventListener('live:render', () => {
            this.initializeTinyMCE();
        });
    }

    initializeTinyMCE() {
        // Supprimer toutes les instances existantes dans la stepZone
        tinymce.remove('.tinymce');

        // Réinitialiser toutes les instances
        const ids = [
            'trace_type_image_description',
            'trace_type_pdf_description',
            'trace_type_lien_description',
            'trace_type_video_description',
        ];

        ids.forEach(id => {
            if (document.getElementById(id)) {
                tinymce.init({
                    selector: `#${id}`,
                    license_key: 'gpl',
                    skin: 'oxide',
                    content_css: 'default',
                    plugins: 'link image code lists table codesample',
                    toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | codesample',
                    menubar: 'edit view format table tools help',
                    language: 'fr_FR',
                });
            }
        });
    }

    setupContentButtons() {
        const addButtonImage = document.querySelector('.add-image');
        if (addButtonImage) {
            addButtonImage.addEventListener('click', () => addImage());
        }

        const addButtonPdf = document.querySelector('.add-pdf');
        if (addButtonPdf) {
            addButtonPdf.addEventListener('click', () => addPdf());
        }

        const addButtonLien = document.querySelector('.add-lien');
        if (addButtonLien) {
            addButtonLien.addEventListener('click', () => addLien());
        }

        const addButtonVideo = document.querySelector('.add-video');
        if (addButtonVideo) {
            addButtonVideo.addEventListener('click', () => addVideo());
        }

        document.querySelectorAll('.delete-image').forEach(button => {
            button.addEventListener('click', () => removeImage(button));
        });

        document.querySelectorAll('.delete-pdf').forEach(button => {
            button.addEventListener('click', () => removePdf(button));
        });

        document.querySelectorAll('.lien_trace').forEach(input => {
            input.parentNode.classList.add('lien_trace_div');

            const wrapperDiv = document.createElement('div');
            wrapperDiv.style.display = 'flex';
            wrapperDiv.style.flexDirection = 'row';

            input.parentNode.insertBefore(wrapperDiv, input);
            wrapperDiv.appendChild(input);

            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'btn btn-danger delete-lien';
            button.innerHTML = '<i class="fa-solid fa-square-minus"></i>';
            wrapperDiv.appendChild(button);
        });

        document.querySelectorAll('.delete-lien').forEach(button => {
            button.addEventListener('click', () => removeLien(button));
        });

        document.querySelectorAll('.video_trace').forEach(input => {
            input.parentNode.classList.add('video_trace_div');
            input.parentNode.innerHTML += (
                '<button type="button" class="btn btn-danger delete-video">' +
                '<i class="fa-solid fa-square-minus"></i>' +
                '</button>'
            );
        });

        document.querySelectorAll('.delete-video').forEach(button => {
            button.addEventListener('click', () => removeVideo(button));
        });
    }

    async up(event) {
        const _value = event.currentTarget.value;
        const page = event.currentTarget.dataset.page;

        const params = new URLSearchParams({ step: 'up', trace: _value, page: page });
        const response = await fetch(`${this.urlValue}?${params.toString()}`);
        this.stepZoneTarget.innerHTML = await response.text();
    }

    async down(event) {
        const _value = event.currentTarget.value;
        const page = event.currentTarget.dataset.page;

        const params = new URLSearchParams({ step: 'down', trace: _value, page: page });
        const response = await fetch(`${this.urlValue}?${params.toString()}`);
        this.stepZoneTarget.innerHTML = await response.text();
    }

    async editTrace(event) {
        const _value = event.currentTarget.value;
        const type = event.currentTarget.dataset.type;
        const page = event.currentTarget.dataset.page;

        const params = new URLSearchParams({ step: 'editTrace', trace: _value, type: type, page: page });
        const response = await fetch(`${this.urlValue}?${params.toString()}`);
        this.stepZoneTarget.innerHTML = await response.text();

        this.initializeTinyMCE();
        this.setupContentButtons();
    }

    async formTrace(event) {
        const _value = event.currentTarget.value;
        const type = event.currentTarget.dataset.type;

        const params = new URLSearchParams({ step: 'formTrace', trace: _value, type: type });
        const response = await fetch(`${this.urlValue}?${params.toString()}`);
        this.stepZoneTarget.innerHTML = await response.text();

        this.initializeTinyMCE();
        this.setupContentButtons();
    }

    async deleteTrace(event) {
        const _value = event.currentTarget.value;
        const page = event.currentTarget.dataset.page;

        const params = new URLSearchParams({ step: 'deleteTrace', trace: _value, page: page });
        if (confirm('Voulez-vous vraiment supprimer cette trace ?')) {
            const response = await fetch(`${this.urlValue}?${params.toString()}`);
            this.stepZoneTarget.innerHTML = await response.text();
        }
    }

    async saveTrace(event) {
        // Sauvegarder le contenu TinyMCE dans les textareas
        tinymce.triggerSave();

        event.preventDefault();
        const _value = event.currentTarget.value;
        const type = event.currentTarget.dataset.type;

        const formData = new FormData(document.getElementById('formTrace'));
        const params = new URLSearchParams({ step: 'saveTrace', trace: _value, type: type });

        await fetch(`${this.urlValue}?${params.toString()}`, {
            method: 'POST',
            body: formData,
        }).then(async fetchResponse => {
            // Nettoyer les erreurs existantes
            document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelector('.competences').style.border = 'none';
            document.getElementById('zone-error').innerHTML = '';

            const contentType = fetchResponse.headers.get('content-type');

            if (contentType && contentType.includes('application/json')) {
                const jsonResponse = await fetchResponse.json();

                if (fetchResponse.status === 500 && jsonResponse.errors?.length > 0) {
                    jsonResponse.errors.forEach(error => {
                        const errorElement = document.createElement('div');
                        errorElement.innerHTML = error.message;
                        errorElement.classList.add('invalid-feedback');

                        const typeMap = {
                            'App\\Components\\Trace\\TypeTrace\\TraceTypeImage': 'trace_type_image',
                            'App\\Components\\Trace\\TypeTrace\\TraceTypePdf': 'trace_type_pdf',
                            'App\\Components\\Trace\\TypeTrace\\TraceTypeLien': 'trace_type_lien',
                            'App\\Components\\Trace\\TypeTrace\\TraceTypeVideo': 'trace_type_video',
                        };

                        const formPrefix = typeMap[type];
                        const field = formPrefix
                            ? document.querySelector(`[name="${formPrefix}[${error.field}]"]`)
                            : null;

                        if (error.field === 'contenu') {
                            const errorContainer = document.querySelector('.contenu-error');
                            errorContainer.style.color = '#dc3545';
                            errorContainer.innerHTML = errorElement.innerHTML;
                        }

                        if (error.field === 'competences') {
                            const errorContainer = document.getElementById('zone-error');
                            const formCompetences = document.querySelector('.competences');
                            errorContainer.style.color = '#dc3545';
                            formCompetences.style.border = '1px solid #dc3545';
                            errorContainer.innerHTML = errorElement.innerHTML;
                        }

                        if (field) {
                            field.parentNode.insertBefore(errorElement, field.nextSibling);
                            field.classList.add('is-invalid');
                        }
                    });
                }
            } else if (fetchResponse.status !== 500) {
                this.stepZoneTarget.innerHTML = await fetchResponse.text();
                createAndShow('success', 'Les modifications ont été enregistrées avec succès.');
            }
        });
    }
}
