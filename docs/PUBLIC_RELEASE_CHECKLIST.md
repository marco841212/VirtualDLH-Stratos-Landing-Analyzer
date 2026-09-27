# Public Release Checklist

Before changing this repository from **Private** to **Public**, complete this review.

- [ ] No `.env` or production configuration files
- [ ] No API tokens, passwords, webhook secrets, cookies, or private keys
- [ ] No database dumps or connection credentials
- [ ] No production server usernames or sensitive absolute paths
- [ ] No private pilot/customer information
- [ ] No raw flight logs that contain identifying information
- [ ] No production screenshots unless intentionally sanitized
- [ ] No proprietary third-party code that cannot be redistributed
- [ ] No dependency licenses that conflict with the MIT License
- [ ] Example telemetry is synthetic or anonymized
- [ ] README installation/configuration instructions are accurate
- [ ] Automated tests pass
- [ ] Secret scanning has been performed on the complete Git history

## If a secret is found

Do not merely delete it in a later commit. Revoke/rotate the credential first, then remove it from the repository and, when necessary, rewrite Git history before public release.
