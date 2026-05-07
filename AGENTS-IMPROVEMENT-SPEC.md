# AGENTS-IMPROVEMENT-SPEC

## Objective
Enable a fully operational autonomous security agent (SASA) within the Sofia Solutions ecosystem using ONA and Gitpod.

## 1. Environment Optimization [HIGH PRIORITY]
- **Status:** Done (.gitpod.yml added).
- **Next:** Verify Docker-in-Docker connectivity in the first ONA/Gitpod run.

## 2. Skill Implementation
- **Action:** Create `.ona/skills/` directory.
- **Skill 1: `threat-analysis`** - Ability to read backend logs and identify attack patterns.
- **Skill 2: `auto-remediation`** - Ability to apply `express-rate-limit` or sanitization patches automatically.

## 3. Communication Layer
- **Action:** Define an API endpoint in `sofia-backend` for the Agent to report findings.
- **Goal:** Findings should appear in the "Security Monitor" (SOC) view of the dashboard.

## 4. Documentation
- Update `MEMORY_BANK.md` to include Agent Decision logs.
