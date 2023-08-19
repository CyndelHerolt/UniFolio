import {Controller} from '@hotwired/stimulus';
import hljs from 'highlight.js';
import 'highlight.js/styles/default.css';

export default class extends Controller {
    static targets = ['zone']

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

    async _changeStep(step) {
        const params = new URLSearchParams({
            step,
        })
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        this.zoneTarget.innerHTML = await response.text()
        this.highlightCode()
    }

    async showPage(event) {
        const _value = event.currentTarget.dataset.page
        const params = new URLSearchParams({
            step: 'page',
            page: _value,
        })
        const response = await fetch(`${this.urlValue}?${params.toString()}`)
        this.zoneTarget.innerHTML = await response.text()
        this.highlightCode()
    }

    highlightCode() {
        document.querySelectorAll('pre code').forEach((block) => {
            hljs.highlightElement(block);
        });
    }
}