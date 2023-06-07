import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["traceform", "competenceform"];

    connect() {
        let selectedChoices = [];
        const formCompetence = this.competenceformTarget;


        const checkboxes= formCompetence.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {

            console.log(checkbox);

            // Écouter les événements de modification des cases à cocher
            checkbox.addEventListener('change', event => {

                if (event.currentTarget.checked) {
                    selectedChoices.push(event.currentTarget);
                }
                else {
                    selectedChoices = selectedChoices.filter(choice => choice !== event.currentTarget);
                }
                console.log(selectedChoices);
            });
        });
    }


    submitForms(event) {
        event.preventDefault();

        // Empêcher la soumission du formulaire formTrace
        event.stopPropagation();

        //afficher un message alert
        alert("Veuillez patienter pendant la sauvegarde de votre trace");

        // Récupérer le formulaire formTrace
        const formTrace = this.traceformTarget;

        // Récupérer le formulaire formCompetence
        const formCompetence = this.competenceformTarget;

        formCompetence.requestSubmit();
        console.log(formCompetence);


        // // Récupérer les input sélectionnés
        // let selectedChoices = [];
        // const checkboxes= formCompetence.querySelectorAll('input[type="checkbox"]');
        // checkboxes.forEach(checkbox => {
        //     if (checkbox.checked) {
        //         selectedChoices.push(checkbox.value);
        //     }
        // });
        //
        // // Mettre à jour les valeurs dans le formulaire competenceform
        // const formData = new FormData(formCompetence);
        // selectedChoices.forEach(choice => {
        //     formData.set('competenceLibelle[]', choice);
        // });
        //
        // console.log(formData);

        // // Soumettre le formulaire formCompetence
        // formCompetence.requestSubmit();
        //
        // // Soumettre le formulaire formTrace
        // formTrace.requestSubmit();
    }
}