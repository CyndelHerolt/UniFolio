import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['navTabs' ,'stepZone', 'page']

    static values = {
        url: String,
        urlSave: String,
    }

    // connect() {
    //     this._changeStep('portfolio')
    // }

    changeStep(event) {
        this._changeStep(event.params.step, event.params.section)
    }

    async addPage(event) {
        const _value = event.currentTarget.value

        // this.stepZoneTarget.innerHTML = window.da.loaderStimulus
        const params = new URLSearchParams({
            step: 'page',
            section: _value,
        })
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        this.stepZoneTarget.innerHTML = await response.text()
        addCallout('Section ajoutée', 'success')
    }

    async _changeStep(step, section) {
        // this.stepZoneTarget.innerHTML = window.da.loaderStimulus
        const params = new URLSearchParams({
            step,
            section,
        })
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        // console.log(response.text());
        this.stepZoneTarget.innerHTML = await response.text()
    }

}
