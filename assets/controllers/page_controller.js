import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['navTabs', 'stepZone', 'page', 'zone', 'traceZone']

    static values = {
        url: String,
        urlSave: String,
    }

    async editPage(event) {
        const _value = event.currentTarget.value

        const params = new URLSearchParams({
            step: 'editPage',
            page: _value,
        })
        console.log(params.toString());
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        console.log(response);
        this.stepZoneTarget.innerHTML = await response.text()
    }

    async addTrace(event) {
        const _value = event.currentTarget.value

        const params = new URLSearchParams({
            step: 'addTrace',
            trace: _value,
        })
        console.log(params.toString());
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        this.traceZoneTarget.innerHTML += await response.text()
    }

}