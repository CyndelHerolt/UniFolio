import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["competenceInput"];

    connect() {
        console.log("Hello, Stimulus!")
    }

    submitForms(event) {
        event.preventDefault();

        console.log('hello')

    }
}
