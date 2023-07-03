import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['navTabs' ,'stepZone', 'page', 'zone', 'traceZone']

    static values = {
        url: String,
        urlSave: String,
    }

    connect() {
        this._changeStep('portfolio')
    }

    changeStep(event) {
        this._changeStep(event.params.step, event.params.section)
    }

    async addPage(event) {
        const _value = event.currentTarget.value

        const params = new URLSearchParams({
            step: 'addPage',
            page: _value,
        })
        console.log(params.toString());
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        this.stepZoneTarget.innerHTML = await response.text()
    }

    async _changeStep(step, page) {
        // this.stepZoneTarget.innerHTML = window.da.loaderStimulus
        const params = new URLSearchParams({
            step,
            page,
        })
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        this.stepZoneTarget.innerHTML = await response.text()
    }



    async save(event) {
        document.getElementById('portfolio')
        const form = document.getElementById('portfolio')
        const dataForm = new FormData(form)

        const params = new URLSearchParams({
            step: 'savePortfolio',
        })

        // Envoyer les données du formulaire via une requête POST
        const response = await fetch(`${this.urlValue}?${params.toString()}`, {
            method: 'POST',
            body: dataForm,
        });
        this.stepZoneTarget.innerHTML = await response.text()
    }

}
