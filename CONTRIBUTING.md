# Contributing

Contributions are welcome.

## Before you submit a change

Please keep the project focused on flight-simulation and virtual-airline use.

Do not submit:

- proprietary Stratos application source code;
- production credentials, API tokens, database dumps, or private keys;
- private pilot/customer information;
- raw production logs or screenshots containing identifying information;
- code you do not have permission to redistribute.

## Development setup

```bash
git clone https://github.com/marco841212/VirtualDLH-Stratos-Landing-Analyzer.git
cd VirtualDLH-Stratos-Landing-Analyzer
composer install
composer test
```

The project requires PHP 8.2 or newer.

## Pull requests

For code changes:

1. Keep the change focused and explain what problem it solves.
2. Add or update PHPUnit tests when behavior changes.
3. Run `composer test` before submitting.
4. Do not overwrite Stratos-provided scores when adding open-source analysis.
5. Keep public report examples synthetic or anonymized.

## Scoring-model changes

Changes to scoring thresholds, category weights, grade bands, or verdict rules should include:

- a clear explanation of the reason for the change;
- updated documentation in `docs/SCORING_MODEL.md`;
- automated tests for the changed behavior.

The project should not describe its simulation scoring thresholds as certified or authoritative real-world aviation limits.

## Security

If you discover a security issue, follow [SECURITY.md](SECURITY.md) instead of publishing sensitive details in a public issue.
