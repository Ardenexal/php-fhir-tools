import { Controller } from '@hotwired/stimulus';

/**
 * "Add another" / "Remove" for repeating Questionnaire items and groups. No hidden `<template>` element
 * is used — the last rendered `[data-repeat-instance]` row for the target linkId is cloned directly
 * (its structure is already correct, including any nested repeat/enableWhen wiring), its form values are
 * cleared, and its index-bearing `name` attributes are bumped to the next free index. Element `id`s in
 * the clone get a per-index suffix, and `label[for]`/`aria-*` references inside the clone follow them, so
 * the page never holds duplicate ids and each label still points at its own row's field.
 *
 * Indices are read from the DOM at add-time, not tracked in JS state, and are never re-numbered after a
 * remove — {@see \App\Sdc\QuestionnaireResponseBuilder} is deliberately sparse-index-tolerant (removing
 * row 1 of [0,1,2] leaves [0,2] on submit, and the builder iterates values rather than assuming
 * contiguity), so there is no correctness reason to renumber, and renumbering would risk clobbering a
 * row a user is mid-edit on.
 */
export default class extends Controller {
    add(event) {
        const linkId = event.params.linkid;
        const wrapper = this.element.querySelector(
            `[data-repeat-field-linkid="${linkId}"], [data-repeat-group-linkid="${linkId}"]`,
        );
        if (!wrapper) {
            return;
        }

        const rows = Array.from(wrapper.querySelectorAll(':scope > [data-repeat-instance]'));
        const lastRow = rows[rows.length - 1];
        if (!lastRow) {
            return;
        }

        const nextIndex = this.nextIndex(rows, linkId);
        const clone = lastRow.cloneNode(true);
        this.reindex(clone, linkId, nextIndex);
        this.clearValues(clone);

        lastRow.after(clone);
    }

    /**
     * Removes the clicked row, except when it is the only row left for its linkId: `add()` clones the
     * last remaining row, so removing it would leave nothing to clone from. That row is cleared instead,
     * which leaves it unanswered on submit just as removing it would.
     */
    remove(event) {
        const row = event.target.closest('[data-repeat-instance]');
        if (!row) {
            return;
        }

        const siblingRows = Array.from(row.parentElement?.children ?? [])
            .filter((el) => el.hasAttribute('data-repeat-instance'));
        if (siblingRows.length <= 1) {
            this.clearValues(row);
            return;
        }

        row.remove();
    }

    /** One past the highest `[<linkId>][<n>]` index currently present, so sparse removes never collide. */
    nextIndex(rows, linkId) {
        let max = -1;
        const pattern = new RegExp(`\\[${this.escapeForRegex(linkId)}\\]\\[(\\d+)\\]`);

        rows.forEach((row) => {
            row.querySelectorAll('[name]').forEach((field) => {
                const match = field.getAttribute('name').match(pattern);
                if (match) {
                    max = Math.max(max, Number(match[1]));
                }
            });
        });

        return max + 1;
    }

    /** Bumps every `[<linkId>][<oldIndex>]` occurrence in the clone's field names to `[<linkId>][<newIndex>]`. */
    reindex(clone, linkId, newIndex) {
        const pattern = new RegExp(`(\\[${this.escapeForRegex(linkId)}\\])\\[\\d+\\]`);

        clone.querySelectorAll('[name]').forEach((field) => {
            field.setAttribute('name', field.getAttribute('name').replace(pattern, `$1[${newIndex}]`));
        });

        this.reindexIds(clone, newIndex);
    }

    /**
     * Gives every id in the clone (including the clone root) a `-r<index>` suffix, replacing any suffix a
     * previously cloned row carried, and rewrites the clone's `for`/`aria-*` references to match.
     */
    reindexIds(clone, newIndex) {
        const renamed = new Map();
        const withIds = [clone, ...clone.querySelectorAll('[id]')].filter((el) => el.hasAttribute('id'));

        withIds.forEach((el) => {
            const oldId = el.getAttribute('id');
            const newId = `${oldId.replace(/-r\d+$/, '')}-r${newIndex}`;
            renamed.set(oldId, newId);
            el.setAttribute('id', newId);
        });

        if (renamed.size === 0) {
            return;
        }

        const refAttributes = ['for', 'aria-labelledby', 'aria-describedby', 'aria-controls'];
        [clone, ...clone.querySelectorAll('*')].forEach((el) => {
            refAttributes.forEach((attr) => {
                const value = el.getAttribute(attr);
                if (value === null) {
                    return;
                }
                el.setAttribute(attr, value.split(/\s+/).map((ref) => renamed.get(ref) ?? ref).join(' '));
            });
        });
    }

    clearValues(clone) {
        clone.querySelectorAll('input, textarea').forEach((field) => {
            field.value = '';
        });
        clone.querySelectorAll('select').forEach((field) => {
            field.selectedIndex = 0;
        });
    }

    escapeForRegex(value) {
        return value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }
}
