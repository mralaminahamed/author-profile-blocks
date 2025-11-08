import { Reporter, TestCase, TestResult } from '@playwright/test/reporter';

interface TestSummary {
    total: number;
    passed: number;
    failed: number;
    skipped: number;
    duration: number;
    tests: Array<{
        title: string;
        status: string;
        duration: number;
        error?: string;
    }>;
}

class SummaryReporter implements Reporter {
    private results: TestSummary = {
        total: 0,
        passed: 0,
        failed: 0,
        skipped: 0,
        duration: 0,
        tests: []
    };

    onTestEnd(test: TestCase, result: TestResult) {
        this.results.total++;
        this.results.duration += result.duration;

        const testInfo = {
            title: test.title,
            status: result.status,
            duration: result.duration,
            error: result.error?.message
        };

        this.results.tests.push(testInfo);

        switch (result.status) {
            case 'passed':
                this.results.passed++;
                break;
            case 'failed':
                this.results.failed++;
                break;
            case 'skipped':
                this.results.skipped++;
                break;
        }
    }

    onEnd() {
        // Write summary to file
        const fs = require('fs');
        const path = require('path');

        const outputDir = path.dirname(this.outputFile);
        if (!fs.existsSync(outputDir)) {
            fs.mkdirSync(outputDir, { recursive: true });
        }

        fs.writeFileSync(this.outputFile, JSON.stringify(this.results, null, 2));
    }

    constructor(private outputFile: string) {}
}

export default SummaryReporter;