import {Controller} from '@hotwired/stimulus';
import {createAndShow} from "../js/_toast";
import hljs from "highlight.js";
import 'highlight.js/styles/default.css';

export default class extends Controller {
    static targets = ['navTabs', 'stepZone', 'page', 'zone', 'traceZone']

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
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        this.stepZoneTarget.innerHTML = await response.text()
        this.highlightCode()
    }

    highlightCode() {
        document.querySelectorAll('pre code').forEach((block) => {
            hljs.highlightElement(block);
        });
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

    async deleteBanniere(event) {
        const params = new URLSearchParams({
            step: 'deleteBanniere',
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
        })
            .then(async fetchResponse => {

                // Remove existing error messages
                document.querySelectorAll('.invalid-feedback').forEach((element) => {
                    element.remove();
                });

                // Remove "is-invalid" class from fields
                document.querySelectorAll('.is-invalid').forEach((element) => {
                    element.classList.remove('is-invalid');
                });

                const contentType = fetchResponse.headers.get('content-type');

                if (contentType && contentType.includes('application/json')) {
                    const jsonResponse = await fetchResponse.json();

                    if (fetchResponse.status === 500 && jsonResponse.errors && jsonResponse.errors.length > 0) {
                        // Loop through each error
                        jsonResponse.errors.forEach((error) => {
                            // Create an error message element
                            const errorElement = document.createElement('div');
                            errorElement.innerHTML = error.message;
                            errorElement.classList.add('invalid-feedback');

                            // Find the form field
                            let field = document.querySelector(`[name="portfolio[${error.field}]"]`);

                            // Append the error message to the form field
                            if (field) {
                                field.parentNode.insertBefore(errorElement, field.nextSibling);

                                // Add "is-invalid" class to the form field
                                field.classList.add("is-invalid");
                            }
                        });
                    }
                } else if (fetchResponse.status !== 500) {
                    this.stepZoneTarget.innerHTML = await fetchResponse.text();
                    createAndShow('success', 'Les modifications ont été enregistrées avec succès.')
                }
            })
    }
}
