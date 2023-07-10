import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['zone']

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

    async _changeStep(step) {
        // this.stepZoneTarget.innerHTML = window.da.loaderStimulus
        const params = new URLSearchParams({
            step,
        })
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        this.zoneTarget.innerHTML = await response.text()
    }

    async showPage(event) {
        // récupérer la valeur de l'attribut data-page
        const _value = event.currentTarget.dataset.page

        const params = new URLSearchParams({
            step: 'page',
            page: _value,
        })
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        this.zoneTarget.innerHTML = await response.text()
    }
}