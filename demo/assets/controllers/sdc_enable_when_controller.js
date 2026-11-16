import { Controller } from '@hotwired/stimulus';

/**
 * Client-side `enableWhen` evaluation, no server round-trip — per M03's Kill Criteria, restricted to
 * the SDC "fixed grammar" (`linkId` + `operator` + one `answer[x]`), with exactly one condition per
 * dependent item (`enableBehavior: all`/`any` across multiple conditions is out of scope; the render
 * model only ever carries the item's FIRST `enableWhen` entry — see `QuestionnaireFormRenderer`).
 *
 * Hiding a dependent item uses the `hidden` attribute, never `disabled` — a `disabled` field is dropped
 * from form submission entirely, which would silently contradict the M03 decision that Extract keeps
 * answers for disabled items (matching `$populate`'s own "enableWhen is a display-time concern, not
 * applied" behavior).
 *
 * Trigger lookup is scoped to this controller's element (the Extract `<form>`) and assumes the trigger
 * itself is non-repeating (a repeating trigger has no defined "the" value to compare against — out of
 * scope here).
 */
export default class extends Controller {
    static targets = ['dependent'];

    connect() {
        this.dependentTargets.forEach((dependentEl) => this.wireDependent(dependentEl));
    }

    wireDependent(dependentEl) {
        let condition;
        try {
            condition = JSON.parse(dependentEl.dataset.sdcEnableWhenConditionValue);
        } catch {
            return;
        }

        const trigger = this.element.querySelector(
            `[data-repeat-field-linkid="${condition.question}"] select, ` +
            `[data-repeat-field-linkid="${condition.question}"] input, ` +
            `[data-repeat-field-linkid="${condition.question}"] textarea`,
        );
        if (!trigger) {
            return;
        }

        const evaluate = () => {
            dependentEl.hidden = !this.evaluateCondition(condition, trigger.value);
        };

        trigger.addEventListener('change', evaluate);
        trigger.addEventListener('input', evaluate);
        evaluate();
    }

    evaluateCondition(condition, actual) {
        if (condition.operator === 'exists') {
            const hasAnswer = actual !== '';

            return condition.answerBoolean === undefined ? hasAnswer : hasAnswer === condition.answerBoolean;
        }

        const expected = this.expectedValue(condition);
        const numericActual = Number(actual);
        const numericExpected = Number(expected);
        const bothNumeric = actual !== '' && expected !== '' && !Number.isNaN(numericActual) && !Number.isNaN(numericExpected);

        switch (condition.operator) {
            case '=':
                return actual === expected;
            case '!=':
                return actual !== expected;
            case '>':
                return bothNumeric && numericActual > numericExpected;
            case '<':
                return bothNumeric && numericActual < numericExpected;
            case '>=':
                return bothNumeric && numericActual >= numericExpected;
            case '<=':
                return bothNumeric && numericActual <= numericExpected;
            default:
                return true;
        }
    }

    /** Reads whichever `answer[x]` key is present and returns it as this codec's form-value string. */
    expectedValue(condition) {
        if ('answerBoolean' in condition) {
            return condition.answerBoolean ? 'true' : 'false';
        }
        if ('answerString' in condition) {
            return condition.answerString;
        }
        if ('answerInteger' in condition) {
            return String(condition.answerInteger);
        }
        if ('answerDecimal' in condition) {
            return String(condition.answerDecimal);
        }
        if ('answerDate' in condition) {
            return condition.answerDate;
        }
        if ('answerCoding' in condition && condition.answerCoding) {
            return condition.answerCoding.code ?? '';
        }

        return '';
    }
}
