import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["traceform", "competenceform"];

    submitForms(event) {
        event.preventDefault();

        console.log("submitForms");

        // Soumettre le formulaire traceform
        this.traceformTarget.requestSubmit();

        // Soumettre le formulaire competenceform
        this.competenceformTarget.requestSubmit();
    }
}