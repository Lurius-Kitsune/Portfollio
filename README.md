# Portfollio

![PHP](https://img.shields.io/badge/PHP-8.4%2B-777BB4?logo=php&logoColor=white)
![Symfony](https://img.shields.io/badge/Symfony-8.1-000000?logo=symfony&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-17-4169E1?logo=postgresql&logoColor=white)
![Webpack Encore](https://img.shields.io/badge/Webpack%20Encore-7-8DD6F9?logo=webpack&logoColor=black)
![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-4-06B6D4?logo=tailwindcss&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?logo=docker&logoColor=white)
![License](https://img.shields.io/badge/license-Proprietary-lightgrey)

A personal portfolio web application built with **Symfony 8** and **Webpack Encore**, showcasing projects and skills through a bilingual (French/English), database-driven interface.

## Overview

Portfollio is a full-stack portfolio site where each project is stored as a database entity (with translatable rich HTML content, media, tags, and metadata) rather than hard-coded in templates. It's containerized with Docker for both local development and production deployment.

## Tech Stack

- **Backend:** PHP 8.4+, Symfony 8.1
- **Database:** PostgreSQL 17, Doctrine ORM + Migrations
- **Translations:** Gedmo Doctrine Extensions (translatable entities) + Symfony Translation component
- **Frontend build:** Webpack Encore, Stimulus, Turbo, Tailwind CSS 4, Flowbite
- **Infrastructure:** Docker, Docker Compose, Nginx, GitHub Actions (image published to GHCR)

## Project Structure

```
.
├── deploy.sh                  # Production deployment script (pull, migrate, rebuild assets)
├── docker-compose.dev.yml     # Local dev stack (nginx + php + postgres, live-mounted source)
├── docker-compose.prod.yml    # Production stack (prebuilt image from GHCR, named volumes)
├── docker/
│   ├── dockerfile / dockerfile.dev
│   └── nginx/
├── sql/
│   └── update.sql             # Manual SQL applied during deployment
└── web/                       # Symfony application
    ├── src/
    │   ├── Controller/        # HomepageController, ProjectController
    │   ├── Entity/            # Project, ProjectType, HardSkill(Type), ProjectMedia, translations
    │   ├── Repository/
    │   ├── Command/           # app:tailwind:extract-classes
    │   └── EventSubscriber/   # Locale handling
    ├── templates/              # Twig templates
    ├── translations/           # homepage/menu/messages/project (en/fr)
    ├── migrations/
    ├── assets/                 # Encore entry points / Stimulus controllers
    └── public/
```

## Data Model

- **Project** — name, slug, type, start/end date, thumbnail, external link, tags, and a translatable `content` field containing rich HTML (rendered on the project detail page), plus related `ProjectMedia` items.
- **ProjectType** — categorizes projects (with its own translation entity).
- **HardSkill / HardSkillType** — skills grouped by category, used on the homepage.

Because `Project::content` stores raw HTML with Tailwind utility classes, a dedicated console command (`app:tailwind:extract-classes`) scans the database and extracts all classes used in that content into `assets/tailwind-content-classes.txt`, so Tailwind's build can pick up classes that don't appear anywhere in the static templates.

## Getting Started (Local Development)

### Prerequisites

- Docker & Docker Compose
- A `.env` file at the repo root defining `POSTGRES_DB`, `POSTGRES_USER`, `POSTGRES_PASSWORD` (and any Symfony `web/.env.local` overrides you need)

### Run the stack

```bash
docker compose -f docker-compose.dev.yml up --build
```

This starts three services:

| Service    | Description                                       | Port |
| ---------- | ------------------------------------------------- | ---- |
| `nginx`    | Serves the app, proxies PHP-FPM                   | 8080 |
| `php`      | PHP-FPM, runs pending Doctrine migrations on boot | 9000 |
| `database` | PostgreSQL 17                                     | 5432 |

The app will be available at `http://localhost:8080`.

### Frontend assets

Inside the `web` container (or locally with Node installed):

```bash
npm install
npm run dev        # one-off build
npm run watch       # rebuild on change
npm run dev-server   # Encore dev server
npm run build        # production build
```

### Useful Symfony/Doctrine commands

```bash
php bin/console doctrine:migrations:migrate
php bin/console app:tailwind:extract-classes
```

## Deployment

Production deployment is handled by `deploy.sh`, which:

1. Loads environment variables from `.env`
2. Pulls the latest code and the latest prebuilt Docker image (published via GitHub Actions to `ghcr.io/lurius-kitsune/portfolio`)
3. Restarts the stack with `docker-compose.prod.yml`
4. Applies `sql/update.sql` against the database
5. Regenerates the Tailwind content-classes file and rebuilds frontend assets inside the running container

```bash
./deploy.sh
```

## Localization

The app supports French and English. `LocaleSubscriber` redirects visitors to their preferred locale (`fr` by default), and both static UI strings (`translations/*.yaml`) and dynamic content (`Project`, `ProjectType`, `HardSkillType`) are translatable.

## License

This project is proprietary / unlicensed — all rights reserved.