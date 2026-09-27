# Changelog

All notable changes to this project will be documented in this file.

The format follows Keep a Changelog principles, and the project uses semantic versioning for public releases.

## [1.0.0] - 2026-09-27

### Added

- Public clean-room PHP 8.2+ compatibility layer for normalized Stratos landing-report data.
- `LandingReport` value object.
- `StratosReportNormalizer` for common report aliases.
- `StratosReportValidator` for structural validation.
- Independent `virtualdlh-default-v1` landing scoring engine.
- Scoring for landing rate, touchdown G-force, bounces, approach stability, rollout/runway-use information, and fuel reserve.
- Non-destructive `virtualdlh_open_source` analysis attachment so existing Stratos scores are preserved.
- Synthetic sample report and standalone scoring example.
- PHPUnit automated tests.
- GitHub Actions CI on PHP 8.2 and 8.3.
- Security guidance and public-release checklist.
- Architecture, report-field, scoring-model, and clean-room implementation documentation.

### Notes

This release does not contain Stratos application source code and does not claim to reproduce Stratos's proprietary scoring algorithm.

The scoring model is intended for flight simulation and virtual-airline use only.
