import {Controller} from '@hotwired/stimulus';

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
    }

    async formTrace(event) {
        const _value = event.currentTarget.value
        const type = event.currentTarget.dataset.type

        const params = new URLSearchParams({
            step: 'formTrace',
            trace: _value,
            type: type,
        })
        console.log(params.toString());
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        console.log(response);
        //remplacer le contenu de la zone de trace définie dans page_controller.js par le contenu de la réponse
        this.stepZoneTarget.innerHTML = await response.text()
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

        const formData = new FormData(document.getElementById('formTrace'));

        const params = new URLSearchParams({
            step: 'saveTrace',
            trace: _value,
            type: type,
        })

        // Envoyer les données du formulaire via une requête POST
        const response = await fetch(`${this.urlValue}?${params.toString()}`, {
            method: 'POST',
            body: formData
        });
        this.stepZoneTarget.innerHTML = await response.text()
    }
}