import {Controller} from '@hotwired/stimulus';
import {createAndShow} from "../js/_toast";
import 'highlight.js/styles/default.css';

export default class extends Controller {
    static targets = ['navTabs', 'stepZone', 'cv', 'zone', 'cvZone']

    static values = {
        url: String,
        urlSave: String,
    }

    connect() {
        console.log('hello');
    }

    async selectedCv(event) {
        console.log('hello');
        const _value = event.currentTarget.value
        // récupérer data-page-id="{{ page.id }}" dans l'option
        const cvId = event.target.options[event.target.selectedIndex].dataset.cvId;

        const params = new URLSearchParams({
            step: 'selectedCv',
            cv: _value,
        })

        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        this.stepZoneTarget.innerHTML = await response.text()
        // Remettre la sélection sur l'option "Choisir..."
        event.target.selectedIndex = 0;
    }

    async deleteCv(event) {
        const _value = event.currentTarget.value

        const params = new URLSearchParams({
            step: 'deleteCv',
            cv: _value,
        })
        if (confirm('Voulez-vous vraiment retirer ce cv ?')) {
            const response = await fetch(`${this.urlValue}?${params.toString()}`)
            this.stepZoneTarget.innerHTML = await response.text()
        }
    }
}
