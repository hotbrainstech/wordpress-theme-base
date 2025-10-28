# WordPress Theme Base – Docker Environment

This repository provides a ready-to-run Docker setup for WordPress theme development. It provisions Nginx, PHP-FPM (based on the official WordPress image), MySQL, and WP-CLI so you can focus on building your theme instead of wiring up infrastructure.

## Prerequisites

- Docker Engine 24+ and the Docker Compose plugin (`docker compose` command)
- Make sure you can allocate at least 2 GB of RAM to Docker Desktop (if applicable)

## Getting Started

1. Copy the environment file and tweak values as needed:
   ```bash
   cp .env.example .env
   ```
   The defaults expose WordPress at `http://localhost:8080` while internally using the host name `wordpress.local`. Update the credentials or host/port if they conflict with other projects.

2. (Optional) Add the host name to your system’s `hosts` file if you plan to use `wordpress.local`:
   ```
   127.0.0.1 wordpress.local
   ```
   You can skip this step and simply browse to `http://localhost:8080` if you prefer.

3. Build the images and start the stack:
   ```bash
   docker-compose up -d --build
   ```

4. Complete the WordPress installation wizard at your chosen URL (default `http://localhost:8080`). Use the database credentials defined in `.env`; the stack already created the database so you can click through without changing them.

5. Activate the included starter theme:
   - Browse to `Appearance → Themes`
   - Locate **WordPress Theme Base** and click **Activate**

The `theme/` directory is mounted into the container at `wp-content/themes/${THEME_SLUG}` (defaults to `theme-base`), so any changes you make locally are reflected immediately.

## Services

| Service    | Image                          | Ports                   | Notes |
|------------|--------------------------------|-------------------------|-------|
| `mysql`    | `mysql:8.0`                    | not exposed externally  | Data persisted in the `mysql_data` volume |
| `wordpress`| `wordpress:6.4.3-php8.2-fpm`   | —                       | Runs PHP-FPM and bundles WP-CLI |
| `nginx`    | `nginx:1.25-alpine`            | `${HOST_HTTP_PORT}:80`  | Serves WordPress via PHP-FPM |

The WordPress and Nginx services share a named volume (`wordpress_data`) for the WordPress core files while mounting your theme from the repository.

## Working with WP-CLI

WP-CLI is preinstalled in the PHP-FPM container. Run one-off commands like so:

```bash
docker compose run --rm wordpress wp core version
docker compose run --rm wordpress wp plugin list
```

Because the theme directory is mounted, WP-CLI commands that interact with themes/plugins operate on your local files.

## Development Tips

- **Theme slug:** Update `THEME_SLUG` in `.env` if you rename the `theme/` directory. WordPress expects the folder name and the theme header (`Text Domain`) to match for translations.
- **Debugging:** `WP_DEBUG` defaults to `true`. Switch it to `false` in `.env` for production-like testing.
- **PHP configuration:** Adjust limits (memory, uploads, execution time) in `docker/php/uploads.ini`.
- **Database access:** Use any MySQL client with the credentials in `.env`. Since the database is not published outside the Docker network, connect via another container, `docker compose exec mysql mysql -u wordpress -p`, or expose the port temporarily by editing `docker-compose.yml`.

## Managing the Stack

- View logs:
  ```bash
  docker compose logs -f nginx
  docker compose logs -f wordpress
  ```
- Stop services without removing data:
  ```bash
  docker compose down
  ```
- Stop services **and** remove volumes (including database content):
  ```bash
  docker compose down --volumes
  ```

## Repository Structure

```
.
├── docker-compose.yml         # Docker stack definition
├── docker/
│   ├── nginx/
│   │   └── default.conf       # Nginx virtual host configuration
│   └── php/
│       ├── Dockerfile         # Extends the WordPress FPM image with WP-CLI
│       └── uploads.ini        # PHP overrides
├── theme/
│   ├── functions.php          # Theme bootstrap
│   ├── index.php              # Basic template placeholder
│   └── style.css              # Theme metadata + global styles
├── .env.example               # Sample environment variables
└── README.md                  # This file
```

Modify or expand the theme files to suit your project. Add build tools (Webpack, Vite, etc.) as needed—Docker does not impose any restrictions on your Node/asset pipeline.
