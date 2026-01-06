# AGENTS.md

This project is a Laravel backend with a Vue frontend. Follow these guidelines when working in this repo.

## Scope and environment
- Assume Laravel is the backend framework and Vite powers the Vue build.
- Use PHP 8+ syntax and conventions; keep code compatible with the existing `composer.json`.
- Use the existing frontend toolchain defined in `package.json` and `vite.config.ts`.
- Prefer `rg` for searches and keep edits within the repo.

## How to work
- Read existing patterns before introducing new ones.
- Avoid broad refactors unless requested; keep changes focused.
- Preserve existing formatting and conventions; do not reformat entire files.
- Add concise comments only when logic is non-obvious.
- Keep context small and avoid copying large blocks unless necessary.

## Laravel conventions
- Controllers should stay thin; move reusable logic into Services, Actions, or Jobs when appropriate.
- Use Form Requests for validation when adding new endpoints.
- Prefer Eloquent relationships and query scopes over raw queries.
- Add or update database migrations and model factories when required.
- Keep policies and authorization checks explicit.
- Use config values via `config()` and environment via `.env` only through config.

## Vue and frontend conventions
- Prefer Composition API and `<script setup>` unless the file already uses Options API.
- Keep components small and focused; extract reusable pieces.
- Co-locate styles with components when appropriate.
- Prefer existing UI patterns and components in the project.
- Avoid inline styles unless already used in the file.

## Testing
- Add or update tests for new behavior.
- Use PHPUnit for backend tests; use the existing frontend test setup if present.
- If tests are not updated, explain why.

## Commands (if needed)
- Backend: `php artisan` and `phpunit`.
- Frontend: `yarn`/`npm` scripts defined in `package.json`.
- Avoid network calls unless approved.

## Outputs and reporting
- Summarize changes with file references.
- Note any assumptions or follow-up tasks.
