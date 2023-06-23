import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['stepZone']

    static values = {
        url: String,
        urlSave: String,
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

    //todo: une méthode pour éditer une nouvelle trace et une méthode pour éditer une trace existante ?

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
        //remplacer le contenu de la zone de trace défini dans page_controller.js par le contenu de la réponse
        this.stepZoneTarget.innerHTML = await response.text()
    }

    async deleteTrace(event) {
        const _value = event.currentTarget.value

        const params = new URLSearchParams({
            step: 'deleteTrace',
            trace: _value,
        })
        if (confirm('Voulez-vous vraiment supprimer cette page ?')) {
            const response = await fetch(`${this.urlValue}?${params.toString()}`)
            this.stepZoneTarget.innerHTML = await response.text()
        }
    }

    async saveTrace(event) {
        const _value = event.currentTarget.value
        const type = event.currentTarget.dataset.type
        const competences = document.getElementById('competences')

        const formData = new FormData(document.getElementById('formTrace'));

        const params = new URLSearchParams({
            step: 'saveTrace',
            trace: _value,
            type: type,
            competences: competences,
        })

        // Envoyer les données du formulaire via une requête POST
        const response = await fetch(`${this.urlValue}?${params.toString()}`, {
            method: 'POST',
            body: formData
        });
        this.stepZoneTarget.innerHTML = await response.text()
    }
}