# VirtualDLH Stratos Landing Analyzer

[![Tests](https://github.com/marco841212/VirtualDLH-Stratos-Landing-Analyzer/actions/workflows/tests.yml/badge.svg)](https://github.com/marco841212/VirtualDLH-Stratos-Landing-Analyzer/actions/workflows/tests.yml)
[![PHP 8.2+](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

Open-source landing analysis and pilot-performance reporting for flight simulation, with compatibility for normalized Stratos landing-report data and VirtualDLH/phpVMS integrations.

## What this project does

VirtualDLH Stratos Landing Analyzer provides an independent PHP 8.2+ toolkit for working with landing reports in virtual-airline and simulator environments.

It can:

- normalize common Stratos landing-report field aliases;
- validate incoming landing-report structures;
- calculate an independent landing score, grade, and verdict;
- score landing rate, touchdown G-force, bounces, approach stability, rollout/runway-use information, and fuel reserve;
- preserve an existing Stratos score while attaching the open-source result separately;
- provide a predictable report structure for VirtualDLH, phpVMS, APIs, and other consumers.

The default open-source scoring model is **`virtualdlh-default-v1`**.

> This repository does **not** contain Stratos application source code and does not reproduce or claim equivalence with Stratos's proprietary scoring algorithm.

## Quick start

Clone the repository and install dependencies:

```bash
git clone https://github.com/marco841212/VirtualDLH-Stratos-Landing-Analyzer.git
cd VirtualDLH-Stratos-Landing-Analyzer
composer install
```

Run the test suite:

```bash
composer test
```

Run the standalone scoring example:

```bash
php examples/score.php
```

## PHP example

```php
<?php

require 'vendor/autoload.php';

use VirtualDLH\StratosLanding\LandingReport;
use VirtualDLH\StratosLanding\Scoring\LandingScorer;

$input = [
    'touchdown' => [
        'landingRateFpm' => -205,
        'gForce' => 1.16,
    ],
    'bounces' => [],
    'stabilityGate' => [
        'evaluated' => true,
        'passed' => true,
        'failures' => [],
    ],
    'runway' => [
        'touchdownFromThresholdFt' => 1350,
        'lengthFt' => 11000,
    ],
    'fuel' => [
        'atLandingLbs' => 15000,
        'burnRateLbsHr' => 12000,
    ],
];

$report = LandingReport::fromArray($input);

$analysis = (new LandingScorer())->score($report->toArray());

print_r($analysis);
```

To attach the open-source analysis without overwriting an existing Stratos score:

```php
$combined = (new LandingScorer())->attach($report->toArray());
```

The result is stored under:

```text
virtualdlh_open_source
```

## Scoring categories

The default model evaluates:

| Category | Default weight |
|---|---:|
| Landing rate | 30 |
| Touchdown G-force | 20 |
| Bounce count | 15 |
| Approach stability | 20 |
| Rollout / runway-use metric | 10 |
| Fuel reserve | 5 |

If a category does not have enough data, it is skipped and the remaining active weights are re-normalized.

See [docs/SCORING_MODEL.md](docs/SCORING_MODEL.md) for the full thresholds, grade bands, and verdict rules.

## Report compatibility

The normalizer recognizes commonly observed fields such as:

- `compositeScore` / `composite_score`
- `grade`
- `landingVerdict`
- `landingRateFpm` / `landing_rate_fpm`
- `gForce` / `g_force`
- `bounces`
- `stabilityGate`
- `approach`
- `touchdown`
- `runway`
- `wind`
- `fuel`

See [docs/REPORT_FIELDS.md](docs/REPORT_FIELDS.md) for the documented report contract.

## Project structure

```text
config/        Safe example configuration
docs/          Architecture, scoring, security, and compatibility documentation
examples/      Synthetic sample reports and usage examples
src/           Normalizer, validator, report model, and scoring engine
tests/         PHPUnit automated tests
.github/       GitHub Actions continuous integration
```

## Automated testing

GitHub Actions runs the PHPUnit suite automatically on PHP **8.2** and **8.3** for pushes to `main` and pull requests.

Local testing:

```bash
composer test
```

## Clean-room implementation

The compatibility layer is based on the observable report contract received by an integration. Compiled or proprietary Stratos application code is not redistributed in this repository.

See [docs/CLEAN_ROOM_IMPLEMENTATION.md](docs/CLEAN_ROOM_IMPLEMENTATION.md).

## Security

Never commit:

- production `.env` files;
- API keys, access tokens, private keys, or passwords;
- database credentials or dumps;
- private pilot/customer information;
- production Stratos logs or screenshots containing identifying information;
- server-specific secrets.

See [SECURITY.md](SECURITY.md).

## Contributing

Issues and pull requests are welcome. Please read [CONTRIBUTING.md](CONTRIBUTING.md) before submitting changes.

For scoring changes, document the reason for the change and add or update automated tests.

## License

Released under the [MIT License](LICENSE).

## Disclaimer

This software is intended for **flight simulation and virtual-airline use only**. The scoring model is not a certified aviation standard, manufacturer limit, regulatory requirement, or substitute for real-world operating procedures.
