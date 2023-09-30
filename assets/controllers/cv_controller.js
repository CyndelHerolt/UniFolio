import {Controller} from '@hotwired/stimulus';
import {createAndShow} from "../js/_toast";
import hljs from "highlight.js";
import 'highlight.js/styles/default.css';

export default class extends Controller {
    static targets = ['navTabs', 'stepZone', 'cv', 'zone', 'cvZone']

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

    async _changeStep(step, cv) {
        // this.stepZoneTarget.innerHTML = window.da.loaderStimulus
        const params = new URLSearchParams({
            step,
            cv,
        })
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        this.stepZoneTarget.innerHTML = await response.text()
    }

    async addCv(event) {
        const _value = event.currentTarget.value

        const params = new URLSearchParams({
            step: 'addCv',
            cv: _value,
        })
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        this.stepZoneTarget.innerHTML = await response.text()
    }

    async selectedCv(event) {
        console.log('hello');
        const _value = event.currentTarget.value
        // récupérer data-page-id="{{ page.id }}" dans l'option
        const cvId = event.target.options[event.target.selectedIndex].dataset.cvId;
        console.log(cvId);

        const params = new URLSearchParams({
            step: 'selectedCv',
            trace: _value,
            cv: cvId,
        })

        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        this.stepZoneTarget.innerHTML = await response.text()
        // Remettre la sélection sur l'option "Choisir..."
        event.target.selectedIndex = 0;
    }
}
