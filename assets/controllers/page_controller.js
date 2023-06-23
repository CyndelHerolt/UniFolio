import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['navTabs', 'stepZone', 'page', 'zone', 'traceZone']

    static values = {
        url: String,
        urlSave: String,
    }

    async left(event) {
        const _value = event.currentTarget.value

        const params = new URLSearchParams({
            step: 'left',
            page: _value,
        })
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        this.stepZoneTarget.innerHTML = await response.text()
    }

    async right(event) {
        const _value = event.currentTarget.value

        const params = new URLSearchParams({
            step: 'right',
            page: _value,
        })
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        this.stepZoneTarget.innerHTML = await response.text()
    }

    async editPage(event) {
        const _value = event.currentTarget.value

        const params = new URLSearchParams({
            step: 'editPage',
            page: _value,
        })
        console.log(params.toString());
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        console.log(response);
        this.stepZoneTarget.innerHTML = await response.text()
    }

    async savePage(event) {
        const _value = event.currentTarget.value

        const formData = new FormData(document.getElementById('formPage'));

        const params = new URLSearchParams({
            step: 'savePage',
            page: _value,
        })

        // Envoyer les données du formulaire via une requête POST
        const response = await fetch(`${this.urlValue}?${params.toString()}`, {
            method: 'POST',
            body: formData
        });
        this.stepZoneTarget.innerHTML = await response.text()
    }

    async deletePage(event) {
        const _value = event.currentTarget.value

        const params = new URLSearchParams({
            step: 'deletePage',
            page: _value,
        })
        if (confirm('Voulez-vous vraiment supprimer cette page ?')) {
            const response = await fetch(`${this.urlValue}?${params.toString()}`)
            this.stepZoneTarget.innerHTML = await response.text()
        }
    }

    async addTrace(event) {
        const _value = event.currentTarget.value
// récupérer data-page-id="{{ page.id }}" dans l'option
        const pageId = event.target.options[event.target.selectedIndex].dataset.pageId;
        console.log(pageId);

        const params = new URLSearchParams({
            step: 'addTrace',
            trace: _value,
            page: pageId,
        })
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        this.traceZoneTarget.innerHTML += await response.text()
    }

}