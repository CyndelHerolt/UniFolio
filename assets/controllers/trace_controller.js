import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['stepZone']

    static values = {
        url: String,
        urlSave: String,
    }

    async editTrace(event) {
        const _value = event.currentTarget.value

        const params = new URLSearchParams({
            step: 'editTrace',
            trace: _value,
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
        //remplacer le contenu de la zone de trace défini dans page_controller.js par le contenu de la réponse
        this.stepZoneTarget.innerHTML = await response.text()
    }
}