# Stratos Landing Report Fields

This document describes fields observed in the current VirtualDLH Stratos landing-report integration. The public analyzer will keep these names where practical so existing VirtualDLH displays remain compatible.

## Top-level report fields

| Field | Purpose |
|---|---|
| `id` | Stable landing-report identifier |
| `pirep_id` | Optional VirtualDLH/phpVMS PIREP association |
| `created_at` | Report timestamp |
| `pilot_ident` | Pilot identifier when integration supplies one |
| `callsign` | Flight callsign |
| `departure_icao` | Departure airport ICAO |
| `arrival_icao` | Arrival airport ICAO |
| `aircraft_type` | Aircraft type/name |
| `aircraft_icao` | Aircraft ICAO type code |
| `aircraft_registration` | Aircraft registration |
| `simulator` | Simulator identifier when available |
| `compositeScore` / `composite_score` | Overall landing score |
| `grade` | Overall grade |
| `landingVerdict` | Human-readable overall landing verdict |
| `landing_rate_fpm` | Normalized landing rate in feet per minute |
| `g_force` | Touchdown G-force |
| `bounce_count` | Number of detected bounces |
| `analysis_available` | Whether a completed analyzer report was received |
| `analysis_status` | Availability state such as `available` or `unavailable` |

## Sections

### `flight`

Observed flight metadata includes callsign, departure/arrival ICAO, aircraft information, and simulator metadata.

### `approach`

Approach telemetry used by the pilot-monitoring display.

### `touchdown`

Observed fields include:

- `landingRateFpm`
- `landingRate` (compatibility/display alias)
- `gForce`
- touchdown airspeed/groundspeed, pitch, and bank when present in the Stratos report

### `bounces`

Array of bounce events. The server also derives `bounce_count` from the array length.

### `stabilityGate`

Observed display fields include:

- `evaluated`
- `passed`
- `failures`

### `runway`

Observed display fields include:

- `ident`
- `lengthFt`
- `headingTrue`
- `touchdownFromThresholdFt`

### `wind`

Observed display fields include:

- `direction` or `directionDeg`
- `speed` or `speedKts`
- `headwind`
- `crosswind`

### `fuel`

Observed display fields include:

- `atLandingLbs`
- `burnRateLbsHr`

### Category scoring

The current pilot-monitoring view recognizes these category names:

- `landingRate`
- `gForce`
- `bounce`
- `approach`
- `rollout`
- `fuel`

Each category may contain a numeric `score`, a `verdict`, or a `skipped` marker.

## Compatibility note

The production VirtualDLH integration currently adds some aliases and PIREP-specific metadata around the Stratos report. The public analyzer should separate those integration aliases from the core Stratos analysis model.
