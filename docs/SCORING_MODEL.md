# Open-Source Scoring Model

The repository includes an original, configurable simulation scoring model named `virtualdlh-default-v1`.

It is **not** the Stratos proprietary scoring algorithm and is not intended to duplicate or claim equivalence with Stratos's internal calculations.

## Default category weights

| Category | Weight |
|---|---:|
| Landing rate | 30 |
| G-force | 20 |
| Bounce | 15 |
| Approach stability | 20 |
| Rollout/runway-use metric | 10 |
| Fuel reserve | 5 |

Categories with unavailable data are skipped. The remaining weights are re-normalized when the composite score is calculated.

## Default landing-rate scoring

The engine uses the absolute landing-rate value in feet per minute.

| Absolute FPM | Score |
|---|---:|
| below 60 | 85 |
| 60–180 | 100 |
| 181–260 | 90 |
| 261–360 | 75 |
| 361–500 | 55 |
| 501–700 | 30 |
| above 700 | 10 |

## Default G-force scoring

| Touchdown G | Score |
|---|---:|
| below 0.95 | 80 |
| 0.95–1.20 | 100 |
| 1.21–1.30 | 90 |
| 1.31–1.40 | 75 |
| 1.41–1.60 | 50 |
| above 1.60 | 25 |

## Bounce scoring

| Bounces | Score |
|---|---:|
| 0 | 100 |
| 1 | 75 |
| 2 | 45 |
| 3+ | 20 |

## Approach stability

If the supplied `stabilityGate` was evaluated and passed, the category receives 100. If it failed, the score begins at 60 and decreases with additional recorded failures. If the stability gate is not available, the category is skipped.

## Rollout/runway-use metric

If `runwayUsedPercent` is available, it is scored directly. Otherwise the engine may derive a simple percentage from `touchdownFromThresholdFt / lengthFt`.

This is a simulation metric, not an operational runway-performance calculation.

## Fuel reserve

When `atLandingLbs` and `burnRateLbsHr` are available, the engine derives approximate reserve minutes and applies the default simulation thresholds.

## Grade and verdict

The weighted composite is converted to a conventional A+ through F grade. Verdicts are:

- 90–100: Excellent
- 80–89.99: Good
- 70–79.99: Fair
- 60–69.99: Marginal
- below 60: Poor

## Important disclaimer

These defaults are designed for virtual-airline and flight-simulation reporting. They are not certified aviation limits, manufacturer guidance, regulatory standards, or a substitute for real-world flight operations procedures.
