import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['traceZone']

    static values = {
        url: String,
        urlSave: String,
    }

    async filtreCompetences(event) {
        const competence = event.currentTarget.value;

        const params = new URLSearchParams({
            competence: competence,
        })
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        this.traceZoneTarget.innerHTML = await response.text()
    }

}