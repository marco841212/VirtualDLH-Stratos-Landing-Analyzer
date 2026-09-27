# VirtualDLH Stratos Landing Analyzer

Advanced flight landing analysis and pilot performance reporting for Stratos and VirtualDLH.

> **Repository status:** Initial public-release preparation. The production VirtualDLH/Stratos integration has **not** been copied into this repository yet. Production code will be reviewed and sanitized before it is added.

## Overview

The VirtualDLH Stratos Landing Analyzer is intended to turn flight telemetry into a structured post-flight landing report for virtual-airline operations and flight-simulation analysis.

Planned/reporting areas include:

- Landing score, grade, and verdict
- Approach and touchdown telemetry
- Vertical speed, IAS/groundspeed, pitch, and bank
- Landing stability checks
- Bounce detection
- Runway-use information
- Wind and fuel context
- Flight-phase/event information
- Aircraft-light state reporting when available from Stratos
- Structured report data that can be linked to a PIREP

## Project goals

1. Keep the landing-analysis engine independent from the VirtualDLH website.
2. Accept normalized Stratos telemetry as input.
3. Produce a predictable, documented report structure.
4. Make integration with phpVMS/VirtualDLH or other systems straightforward.
5. Keep credentials, pilot-private data, raw production logs, and server configuration out of the repository.

## Repository layout

```text
config/        Example configuration only
docs/          Architecture and public-release documentation
examples/      Sanitized sample data and integration examples
src/           Landing analyzer source code
tests/         Automated tests
```

## Security

Do **not** commit production `.env` files, database credentials, API tokens, private pilot information, raw Stratos logs, production screenshots, or server-specific secrets.

See [SECURITY.md](SECURITY.md) and [docs/PUBLIC_RELEASE_CHECKLIST.md](docs/PUBLIC_RELEASE_CHECKLIST.md).

## Configuration

Copy `.env.example` to `.env` for local development and supply your own values. The real `.env` file is ignored by Git.

## License

This project is released under the MIT License. See [LICENSE](LICENSE).

## Disclaimer

This software is intended for flight-simulation and virtual-airline use. It is not an approved real-world aviation safety or flight-data analysis system.

## Reference implementation

The repository includes a clean-room PHP 8.2+ compatibility layer under `src/`. It normalizes and validates the observable Stratos landing-report schema for downstream applications without redistributing Stratos application source code or claiming to reproduce Stratos's proprietary scoring algorithm.

See [docs/CLEAN_ROOM_IMPLEMENTATION.md](docs/CLEAN_ROOM_IMPLEMENTATION.md).


## Independent open-source scoring engine

The project now includes an original scoring engine under `src/Scoring/`. It can score normalized landing data across landing rate, touchdown G-force, bounce count, approach stability, rollout/runway-use information, and fuel reserve.

The open-source score is stored separately under `virtualdlh_open_source` when attached to an existing report, so it does not overwrite a Stratos-provided score, grade, or verdict.

The default model is `virtualdlh-default-v1`. Its formulas and thresholds are documented in [docs/SCORING_MODEL.md](docs/SCORING_MODEL.md) and are intended only for flight simulation.

### Development

```bash
composer install
composer test
php examples/score.php
```
