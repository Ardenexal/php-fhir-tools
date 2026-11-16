import { Controller } from '@hotwired/stimulus';

/**
 * Toggles the "paste your own Questionnaire JSON" textarea on the SDC sample picker. Previously an
 * inline onchange= attribute; converted to Stimulus because inline handlers don't survive a Turbo Frame
 * swap re-rendering this form from scratch, whereas data-controller attributes reconnect automatically.
 * The visibility is also applied on connect, so a form re-rendered with "custom" already selected shows
 * the textarea without waiting for a change event.
 */
export default class extends Controller {
    static targets = ['customJson', 'source'];

    connect() {
        if (this.hasSourceTarget) {
            this.apply(this.sourceTarget.value);
        }
    }

    toggleCustom(event) {
        this.apply(event.target.value);
    }

    apply(source) {
        this.customJsonTarget.classList.toggle('hidden', source !== 'custom');
    }
}
