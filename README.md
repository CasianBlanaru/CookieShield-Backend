# CookieShield Backend

A Laravel-based API server for the CookieShield consent management system. This server provides endpoints for retrieving configurations and storing consent decisions.

## Motivation

CookieShield was developed to create a 100% GDPR (DSGVO) and BITV compliant cookie consent solution that is completely free to use. Our goal is to provide a robust tool that allows website owners to meet legal requirements while maintaining full control over the design and functionality of their cookie banners. Privacy compliance should be accessible to everyone, not just those who can afford premium solutions.

## Features

- 100% GDPR (DSGVO) and BITV compliant consent management
- Free to use with all features included - no premium tiers
- REST API for cookie consent configurations
- Store and retrieve consent decisions with proper documentation
- Administrative interface for managing configurations
- Multi-language support for international websites
- JSON-based configuration for flexible customization
- Complete design freedom through the dashboard

## Requirements

- PHP 8.1+
- Composer
- MySQL, PostgreSQL, or SQLite

## Installation

1. Clone the repository
2. Install dependencies:

```bash
composer install
```

3. Configure the `.env` file (copy from `.env.example`):

```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database connection in the `.env` file:

```
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

Or for MySQL:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cookieshield
DB_USERNAME=root
DB_PASSWORD=
```

5. Run database migrations:

```bash
php artisan migrate
```

6. Seed the database:

```bash
php artisan db:seed
```

## Starting the Server

Start the development server:

```bash
php artisan serve
```

The server will be accessible at `http://localhost:8000`.

## API Endpoints

### Configuration

- `GET /api/prod/config.json?apiKey=your-api-key&v=1.0.0`: Retrieve configuration
- `POST /api/prod/config`: Save configuration

### Consent

- `POST /api/prod/consent`: Store consent decision
- `GET /api/prod/consent/{visitorId}?apiKey=your-api-key`: Retrieve consent for a visitor
- `DELETE /api/prod/consent/{visitorId}?apiKey=your-api-key`: Delete consent for a visitor

### Admin Area

- `GET /api/admin/configs`: List all configurations
- `GET /api/admin/configs/{id}`: Show a specific configuration
- `PUT /api/admin/configs/{id}`: Update a configuration
- `DELETE /api/admin/configs/{id}`: Delete a configuration
- `GET /api/admin/consents`: List all consents
- `GET /api/admin/consents/{id}`: Show a specific consent
- `GET /api/admin/consents/api-key/{apiKey}`: List consents for an API key

## Integration with the CookieShield Dashboard

In the client (`config-example.js`), adjust the API endpoints:

```javascript
window.__COOKIE_BANNER_SETTINGS__ = {
  apiKey: 'your-api-key',
  // ...
  apiEndpoints: {
    config: 'http://localhost:8000/api/prod',
    consent: 'http://localhost:8000/api/prod/consent',
    location: 'http://localhost:8000/api/prod/location'
  }
};
```

## Production Environment

For production environments, we recommend:

1. Setting up a proper web server (Nginx, Apache)
2. Configuring SSL/TLS
3. Setting up rate limiting and caching
4. Protecting admin routes with authentication

## Developer

Developed by Casian Blanaru at PixelCoda.

- Email: casian@casianus.com
- Company: PixelCoda

## License

This project is licensed under the MIT License. See the LICENSE file for details.
