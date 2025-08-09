# Unick Enterprises Inc. - Order, Inventory, Production System

This repository contains a full-stack implementation using Laravel 11 (backend) and React 18 + Vite + Bootstrap 5 (frontend).

## Features
- Inventory Management with MRP-style forecasting
- Production tracking and schedule forecasting
- Order processing with customer portal
- Exports to Excel (Laravel Excel) and PDF (DOMPDF)
- Authentication via Laravel Sanctum

## Monorepo Structure
```
backend/
  app/
  database/
  routes/api.php
frontend/
  src/
  vite.config.js
```

## Backend Setup (Laravel 11 + MySQL)
1. Install PHP 8.2+, Composer, and MySQL.
2. In `backend/`, run:
   - `composer install`
   - `cp .env.example .env` and set DB credentials
   - `php artisan key:generate`
   - `php artisan migrate --seed`
   - `php artisan serve`
3. Sanctum: Ensure `SANCTUM_STATEFUL_DOMAINS=localhost,localhost:5173` and set `SESSION_DOMAIN=localhost`.

### Predictive Analytics
- Implemented in `app/Services/AnalyticsService.php` using moving average of consumption from `order_items` plus baseline `consumption_rate_per_day`.
- Endpoints:
  - `GET /api/inventory/{id}/forecast`
  - `GET /api/productions/schedule/forecast`

### Exports
- Excel: `maatwebsite/excel`
- PDF: `barryvdh/laravel-dompdf`
- Endpoints under `/api/export/*`.

### Auth Endpoints
- `POST /api/auth/register`
- `POST /api/auth/login`
- `POST /api/auth/logout` (requires auth)

## Frontend Setup (React 18 + Vite)
1. In `frontend/`, run:
   - `npm install`
   - Create `.env` with `VITE_API_BASE_URL=http://localhost:8000/api`
   - `npm run dev`
2. Login/Register to obtain a token stored in `localStorage`.

## API Integration
- Axios instance in `src/services/api.js` auto-attaches `Authorization: Bearer <token>`.
- Routes defined in `src/App.jsx`.

## Export Features
- Buttons in admin pages call `/api/export/*` endpoints for Excel and PDF downloads.

## Deployment
- Backend: deploy Laravel to [DEPLOYMENT_ENV] (set APP_KEY, DB creds, run migrations, queue workers if needed).
- Frontend: `npm run build` and serve `dist/` behind a web server or host as static assets.

## Postman Collection
- See `docs/Unick.postman_collection.json` for sample API requests.