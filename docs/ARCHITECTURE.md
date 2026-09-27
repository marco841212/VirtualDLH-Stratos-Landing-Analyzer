# Architecture

The public version of the Stratos Landing Analyzer is being separated from the production VirtualDLH Crew Center so the analysis engine can be reviewed, tested, and reused without exposing website-specific configuration.

## Intended flow

```text
Stratos telemetry
       |
       v
Telemetry normalization
       |
       v
Landing event / phase analysis
       |
       v
Scoring + stability checks
       |
       v
Structured landing report
       |
       +--> VirtualDLH / phpVMS PIREP integration
       +--> JSON/API consumers
       +--> Human-readable pilot report
```

## Separation of concerns

### Analyzer core
Contains calculations and rules that can operate on normalized flight data.

### Stratos adapter
Converts Stratos-specific telemetry/events into the analyzer's normalized input.

### Report formatter
Produces a structured report suitable for storage, API output, or presentation.

### VirtualDLH integration
Associates the analyzed report with a PIREP and displays it in the Crew Center. This integration should remain optional so the analyzer can be reused independently.

## Public-release principle

Production database access, application credentials, private logs, screenshots, and server-specific paths do not belong in the analyzer core.
