// Builds the markdown body for the PR benchmark comment posted by
// .github/workflows/pr.yml.
//
// The workflow renders phpbench's `ci_summary` report (defined in phpbench.json)
// with `--output=json`, which gives us unformatted numbers: times in
// microseconds, memory in bytes, rstdev as a percentage. Everything a human
// reads -- units, rounding, grouping, table layout -- is decided here, so the
// comment can be a real markdown table instead of a fenced dump of console art.
//
// Kept out of the workflow YAML so it can be exercised directly with node
// against a recorded phpbench payload; see the bottom of this file for the
// self-check entry point.

'use strict';

// Stable marker so the workflow can update this PR's existing comment in place
// instead of stacking one per push. The benchmark job is not a matrix, so a
// single unscoped marker is safe here.
const MARKER = '<!-- phpbench-benchmark-results -->';

// GitHub rejects comment bodies over 65536 characters. Benchmark tables are
// small, but the raw phpbench log grows with every added subject and with any
// error backtrace, so it is the part we sacrifice when we run out of room.
const MAX_BODY = 65536;
const BODY_HEADROOM = 2048;

// A relative standard deviation above this is noise rather than signal: the
// number is real, but it should not be read as a performance change.
const NOISY_RSTDEV = 15;

/**
 * Format a phpbench time (microseconds) with a unit a reader can scan.
 */
function formatTime(microseconds) {
    if (typeof microseconds !== 'number' || !Number.isFinite(microseconds)) {
        return '—';
    }

    if (microseconds >= 1000000) {
        return `${(microseconds / 1000000).toFixed(3)} s`;
    }

    if (microseconds >= 1000) {
        return `${(microseconds / 1000).toFixed(3)} ms`;
    }

    // Sub-microsecond results are common for cache-hit subjects, where three
    // decimals is the difference between "0 µs" and a usable number.
    return `${microseconds.toFixed(3)} µs`;
}

/**
 * Format a phpbench memory reading (bytes) as MB/KB.
 */
function formatMemory(bytes) {
    if (typeof bytes !== 'number' || !Number.isFinite(bytes)) {
        return '—';
    }

    if (bytes >= 1048576) {
        return `${(bytes / 1048576).toFixed(2)} MB`;
    }

    if (bytes >= 1024) {
        return `${(bytes / 1024).toFixed(2)} KB`;
    }

    return `${bytes} B`;
}

/**
 * Format a relative standard deviation, flagging readings too noisy to trust.
 */
function formatRstdev(percent) {
    if (typeof percent !== 'number' || !Number.isFinite(percent)) {
        return '—';
    }

    const rendered = `±${percent.toFixed(2)}%`;

    return percent > NOISY_RSTDEV ? `${rendered} ⚠️` : rendered;
}

/**
 * Parse the JSON renderer's output.
 *
 * phpbench's JSON renderer writes one JSON array per report table, each on its
 * own line, so the payload is a stream of arrays rather than a single document.
 * `ci_summary` declares no `break`, so today that is one line -- parsing every
 * line keeps this correct if a `break` is ever added.
 *
 * @returns {Array<object>|null} null when the payload is missing or unparseable
 */
function parseRows(payload) {
    if (typeof payload !== 'string' || payload.trim() === '') {
        return null;
    }

    const rows = [];

    for (const line of payload.split('\n')) {
        const trimmed = line.trim();

        if (trimmed === '') {
            continue;
        }

        let parsed;
        try {
            parsed = JSON.parse(trimmed);
        } catch (error) {
            return null;
        }

        if (!Array.isArray(parsed)) {
            return null;
        }

        rows.push(...parsed);
    }

    return rows;
}

/**
 * Group rows by benchmark class, preserving the order phpbench reported them in.
 *
 * @returns {Array<{benchmark: string, rows: Array<object>}>}
 */
function groupByBenchmark(rows) {
    const groups = new Map();

    for (const row of rows) {
        const benchmark = row.benchmark || 'Unknown benchmark';

        if (!groups.has(benchmark)) {
            groups.set(benchmark, []);
        }

        groups.get(benchmark).push(row);
    }

    return [...groups].map(([benchmark, grouped]) => ({ benchmark, rows: grouped }));
}

/**
 * Render one benchmark class as a markdown table.
 *
 * The `set` column only earns its place when some subject is parameterised;
 * every subject in this suite is currently unparameterised, so it is dropped.
 */
function renderTable(rows) {
    const hasSets = rows.some((row) => typeof row.set === 'string' && row.set !== '');

    const headers = ['Subject'];
    const alignment = [':---'];

    if (hasSets) {
        headers.push('Set');
        alignment.push(':---');
    }

    headers.push('Mode', 'Best', 'Worst', 'RSD', 'Mem peak', 'Revs', 'Its');
    alignment.push('---:', '---:', '---:', '---:', '---:', '---:', '---:');

    const lines = [`| ${headers.join(' | ')} |`, `| ${alignment.join(' | ')} |`];

    for (const row of rows) {
        const cells = [`\`${row.subject ?? '—'}\``];

        if (hasSets) {
            cells.push(row.set === '' ? '—' : `\`${row.set}\``);
        }

        cells.push(
            formatTime(row.mode),
            formatTime(row.best),
            formatTime(row.worst),
            formatRstdev(row.rstdev),
            formatMemory(row.mem_peak),
            String(row.revs ?? '—'),
            String(row.its ?? '—'),
        );

        lines.push(`| ${cells.join(' | ')} |`);
    }

    return lines;
}

/**
 * A collapsed block of preformatted text.
 *
 * The blank line after `</summary>` is load-bearing: without it GitHub treats
 * the following block as raw HTML content and stops rendering markdown inside
 * the `<details>`.
 */
function renderDetails(summary, preformatted) {
    return [
        '<details>',
        `<summary>${summary}</summary>`,
        '',
        '```',
        preformatted.replace(/```/g, "`` `"),
        '```',
        '',
        '</details>',
    ];
}

/**
 * Build the full comment body.
 *
 * @param {object} input
 * @param {string|null} input.summaryJson       contents of the ci_summary JSON render
 * @param {string|null} input.rawOutput         contents of the console phpbench log
 * @param {boolean}     input.baselineExists    whether a stored baseline was found
 * @param {string|null} input.regressionStatus  'passed', 'regressed', or anything
 *     else for a check that did not produce a verdict
 * @param {string|null} input.regressionExitCode phpbench's exit code, quoted in the
 *     error case so the log is easier to find
 * @param {string|null} input.regressionOutput
 * @param {string|null} input.runUrl            link back to the workflow run
 */
function buildBody(input) {
    const {
        summaryJson = null,
        rawOutput = null,
        baselineExists = false,
        regressionStatus = null,
        regressionExitCode = null,
        regressionOutput = null,
        runUrl = null,
    } = input;

    const rows = parseRows(summaryJson);
    const body = [MARKER, '## 📊 Benchmark Results', ''];

    if (rows === null || rows.length === 0) {
        body.push(
            rows === null
                ? '⚠️ Could not read the machine-readable benchmark summary, so the results below are unformatted phpbench output.'
                : '⚠️ The benchmark run produced no results.',
            '',
        );
    } else {
        const benchmarks = groupByBenchmark(rows);
        const noisy = rows.filter(
            (row) => typeof row.rstdev === 'number' && row.rstdev > NOISY_RSTDEV,
        ).length;

        const subjectWord = rows.length === 1 ? 'subject' : 'subjects';
        const benchmarkWord = benchmarks.length === 1 ? 'benchmark' : 'benchmarks';
        body.push(
            `**${rows.length} ${subjectWord}** across **${benchmarks.length} ${benchmarkWord}**. ` +
                '`Mode` is the kde mode of the per-revolution average times; `RSD` is the relative standard deviation across iterations.',
            '',
        );

        if (noisy > 0) {
            const readingWord = noisy === 1 ? 'reading' : 'readings';
            body.push(
                `> ⚠️ ${noisy} ${readingWord} above ±${NOISY_RSTDEV}% RSD — too noisy on shared CI runners to read as a performance change.`,
                '',
            );
        }

        for (const { benchmark, rows: grouped } of benchmarks) {
            body.push(`### ${benchmark}`, '', ...renderTable(grouped), '');
        }
    }

    if (baselineExists) {
        // Anything other than a clean pass or a failed assertion is a check that
        // produced no verdict -- a subject that threw, a bad flag, a missing
        // baseline tag. Reporting those as a regression would blame the PR for a
        // broken harness, so they get their own state.
        const outcomes = {
            passed: ['✅', 'Regression check passed', null],
            regressed: [
                '❌',
                'Regression detected (>50% slower than baseline)',
                null,
            ],
        };
        const [icon, label, note] = outcomes[regressionStatus] ?? [
            '⚠️',
            'Regression check did not run',
            `phpbench exited ${regressionExitCode ?? 'non-zero'} without asserting, so this says nothing about the performance of these changes. The step log has the reason.`,
        ];

        body.push(`### ${icon} ${label}`, '');

        if (note) {
            body.push(`> ${note}`, '');
        }

        if (regressionOutput) {
            body.push(...renderDetails('Comparison vs baseline', regressionOutput), '');
        }
    } else {
        body.push(
            '> ℹ️ No baseline stored yet. Run `composer bench:baseline` and commit `.phpbench/` to enable regression checks.',
            '',
        );
    }

    const footer = ['---', ''];

    if (runUrl) {
        footer.push(
            `Run \`composer bench\` locally for full results, or open the [workflow run](${runUrl}).`,
        );
    } else {
        footer.push('Run `composer bench` locally for full results.');
    }

    footer.push('', '*This comment was automatically generated by the GitHub Actions workflow.*');

    // The raw log is the only unbounded part of the body, so it is fitted last
    // and trimmed from the front (keeping the tail, where failures surface).
    if (rawOutput && rawOutput.trim() !== '') {
        const fixedLength = [...body, ...footer].join('\n').length;
        const available = MAX_BODY - BODY_HEADROOM - fixedLength;
        let log = rawOutput;

        if (available <= 0) {
            log = null;
        } else if (log.length > available) {
            log = `…truncated…\n${log.slice(log.length - available + 16)}`;
        }

        if (log !== null) {
            body.push(...renderDetails('📋 Raw phpbench output', log), '');
        }
    }

    return [...body, ...footer].join('\n');
}

module.exports = {
    MARKER,
    NOISY_RSTDEV,
    buildBody,
    formatMemory,
    formatRstdev,
    formatTime,
    groupByBenchmark,
    parseRows,
};

// Self-check: `node .github/scripts/benchmark-comment.js`, which exits non-zero
// on failure. Not wired into the workflow -- no job here installs Node, and the
// `github-script` step brings its own runtime -- so this is for running by hand
// after editing. Needs no phpbench run: the fixture below is a trimmed copy of a
// real `--report=ci_summary --output=json` payload.
if (require.main === module) {
    const FIXTURE = JSON.stringify([
        {
            benchmark: 'SerializationJsonBench',
            subject: 'benchJsonSerializeBundle',
            set: '',
            revs: 10,
            its: 5,
            mem_peak: 23135080,
            best: 319.5,
            mode: 330.22853,
            worst: 341,
            rstdev: 3.2649,
        },
        {
            benchmark: 'SerializationJsonBench',
            subject: 'benchJsonDeserializeBundle',
            set: '',
            revs: 10,
            its: 5,
            mem_peak: 23162000,
            best: 987,
            mode: 993.7632,
            worst: 1120,
            rstdev: 21.0084,
        },
        {
            benchmark: 'FHIRPathParsingBench',
            subject: 'benchParseSimple',
            set: '',
            revs: 10,
            its: 5,
            mem_peak: 2257632,
            best: 14.5,
            mode: 15,
            worst: 15.5,
            rstdev: 3.3333,
        },
    ]);

    const failures = [];
    const check = (label, condition) => {
        if (!condition) {
            failures.push(label);
        }
    };

    check('microseconds keep their unit', formatTime(15) === '15.000 µs');
    check('milliseconds are scaled', formatTime(1571.9999) === '1.572 ms');
    check('seconds are scaled', formatTime(2500000) === '2.500 s');
    check('missing times degrade', formatTime(null) === '—');
    check('megabytes are scaled', formatMemory(23135080) === '22.06 MB');
    check('kilobytes are scaled', formatMemory(2048) === '2.00 KB');
    check('quiet readings are unflagged', formatRstdev(3.2649) === '±3.26%');
    check('noisy readings are flagged', formatRstdev(21.0084).endsWith('⚠️'));
    check('a broken payload is detected', parseRows('not json') === null);
    check('an absent payload is detected', parseRows('') === null);
    check('rows group by benchmark', groupByBenchmark(JSON.parse(FIXTURE)).length === 2);

    const RUN_URL = 'https://github.com/o/r/actions/runs/1';
    const body = buildBody({
        summaryJson: FIXTURE,
        rawOutput: 'raw phpbench console output',
        baselineExists: true,
        regressionStatus: 'passed',
        regressionOutput: 'baseline comparison',
        runUrl: RUN_URL,
    });

    check('the marker leads the body', body.startsWith(MARKER));
    check('a table header is rendered', body.includes('| Subject | Mode | Best | Worst | RSD | Mem peak | Revs | Its |'));
    check('a table row is rendered', body.includes('| `benchParseSimple` | 15.000 µs |'));
    check('each benchmark gets a heading', body.includes('### FHIRPathParsingBench'));
    check('the noise warning is summarised', body.includes('1 reading above ±15% RSD'));
    check('a passing baseline check is reported', body.includes('✅ Regression check passed'));
    // Compares the href the footer actually emitted rather than looking for the
    // URL anywhere in the body -- the raw log could contain it too.
    check('the run is linked', body.match(/\[workflow run\]\((.+?)\)/)?.[1] === RUN_URL);
    // Without this blank line GitHub renders the block as literal HTML.
    check('details blocks breathe', !/<\/summary>\n```/.test(body));
    check('markdown survives inside details', body.split('<summary>').length === 3);
    check('the body fits GitHub\'s limit', body.length <= MAX_BODY);

    const noBaseline = buildBody({ summaryJson: FIXTURE, rawOutput: null, baselineExists: false });
    check('a missing baseline is explained', noBaseline.includes('No baseline stored yet'));

    const regressed = buildBody({
        summaryJson: FIXTURE,
        baselineExists: true,
        regressionStatus: 'regressed',
        regressionExitCode: '2',
    });
    check('a regression is reported', regressed.includes('❌ Regression detected'));

    // The bug this guards: phpbench crashing used to render as a regression,
    // because an unset step output compared unequal to '0'.
    for (const status of ['error', '', null, undefined]) {
        const broken = buildBody({
            summaryJson: FIXTURE,
            baselineExists: true,
            regressionStatus: status,
            regressionExitCode: '1',
        });
        check(`a check with no verdict (${JSON.stringify(status)}) is not a regression`, broken.includes('⚠️ Regression check did not run'));
        check(`a check with no verdict (${JSON.stringify(status)}) is not blamed on the PR`, !broken.includes('Regression detected'));
        check(`a check with no verdict (${JSON.stringify(status)}) names the exit code`, broken.includes('phpbench exited 1'));
    }

    const brokenNoCode = buildBody({
        summaryJson: FIXTURE,
        baselineExists: true,
        regressionStatus: 'error',
    });
    check('an unknown exit code still reads sensibly', brokenNoCode.includes('phpbench exited non-zero'));

    const noJson = buildBody({ summaryJson: null, rawOutput: 'console only', baselineExists: false });
    check('an unreadable payload falls back to the raw log', noJson.includes('Could not read the machine-readable benchmark summary'));
    check('the raw log is still attached on fallback', noJson.includes('console only'));

    const huge = buildBody({
        summaryJson: FIXTURE,
        rawOutput: `${'x'.repeat(400000)}\nFATAL: the tail must survive\n`,
        baselineExists: false,
    });
    check('an oversized log is trimmed to fit', huge.length <= MAX_BODY);
    check('trimming is disclosed', huge.includes('…truncated…'));
    check('trimming keeps the tail', huge.includes('FATAL: the tail must survive'));
    check('trimming keeps the table', huge.includes('| `benchParseSimple` |'));

    if (failures.length > 0) {
        console.error(`benchmark-comment self-check failed:\n  - ${failures.join('\n  - ')}`);
        process.exit(1);
    }

    console.log('benchmark-comment self-check passed');
}
