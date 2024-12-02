# Blogabet Picks Email Bot

## Description
This is a **Laravel** project designed to:
1. Automatically parse picks received via Gmail from **Blogabet**.
2. Forward the parsed picks via email using **Pipedream**.

The project streamlines the process by combining Laravel's backend capabilities with external integrations to automate pick processing and distribution.

## Requirements
- **PHP >= 8.0**
- **Composer** installed.
- A database compatible with Laravel (MySQL, PostgreSQL, etc.).
- Gmail API configured.
- A **Pipedream** account for handling email forwarding.

## Installation
Clone the repository:
```
   "git clone https://github.com/your-repo/blogabet-picks-bot.git
   cd blogabet-picks-bot"
  ``` 
Install dependencies with Composer:
```
composer install
```
Configure environment variables:

Rename the .env.example file to .env:
```
cp .env.example .env
```
Update the .env file with your settings, including:
Database connection details.
Gmail API credentials.
Pipedream webhook URL.
Run database migrations:
```
php artisan migrate
```
Seed test data (example inactive API key):
```
php artisan db:seed --class=ApiKeySeeder
```
Generate the application key:
```
php artisan key:generate
```