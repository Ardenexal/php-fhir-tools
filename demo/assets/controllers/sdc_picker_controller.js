import { Controller } from '@hotwired/stimulus';

/**
 * Toggles the "paste your own Questionnaire JSON" textarea on the SDC sample picker. Previously an
 * inline onchange= attribute; converted to Stimulus because inline handlers don't survive a Turbo Frame
 * swap re-rendering this form from scratch, whereas data-controller attributes reconnect automatically.
 */
export default class extends Controller {
    static targets = ['customJson'];

    toggleCustom(event) {
        this.customJsonTarget.classList.toggle('hidden', event.target.value !== 'custom');
    }
}
