import { Controller } from '@hotwired/stimulus'
import { Tooltip } from 'bootstrap'

export default class extends Controller {
    connect() {
        const tooltip = new Tooltip(this.element, {
            trigger: 'hover',
            placement: this.element.dataset.bsPlacement ?? 'bottom',
        })
    }
}