# Clean-Room Implementation

This repository does not contain copied Stratos application source code.

The reference implementation is built only from the observable landing-report contract received by VirtualDLH, including field names and values exposed through the normal Stratos/VirtualDLH integration.

The installed Stratos Electron application contains compiled application bundles. Those files are not redistributed here.

## Goal

Provide an independent compatibility layer that can:

- accept a Stratos landing report already delivered to an integration;
- normalize common aliases into a stable schema;
- validate the expected report structure;
- expose the report to VirtualDLH/phpVMS or other consumers.

This repository does not claim to reproduce Stratos's proprietary scoring algorithm. Any future open-source scoring engine should document its own formulas and thresholds explicitly.
