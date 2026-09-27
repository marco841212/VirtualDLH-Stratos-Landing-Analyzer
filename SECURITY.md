# Security Policy

## Reporting a security issue

Please do not publish credentials, tokens, production logs, private pilot information, or exploitable security details in a public GitHub issue.

Before this repository is made public, all production-derived source files must be reviewed for:

- API keys and access tokens
- Database usernames and passwords
- Private URLs or authentication endpoints
- Server usernames and absolute hosting paths
- Pilot names, email addresses, IDs, or other private data
- Raw flight logs or screenshots containing identifying information
- Session cookies, headers, webhook secrets, or signing keys

If a secret is ever committed, removing the file in a later commit is not enough. The secret should be revoked/rotated because it may remain in Git history.
