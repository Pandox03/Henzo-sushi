# Queue Setup for Email Sending

## Overview
The promo code email system now uses Laravel queues to send emails in the background. This prevents rate limiting issues and provides better performance.

## Setup Instructions

### 1. Configure Queue Connection

Open your `.env` file and set:

```env
QUEUE_CONNECTION=database
```

**Note:** By default, Laravel uses `sync` which sends emails immediately. Change it to `database` to use queues.

### 2. Run Migrations (Already Done)

The queue tables have already been created. If you need to recreate them:

```bash
php artisan migrate
```

### 3. Start the Queue Worker

**Important:** You need to run the queue worker in a separate terminal/process to process queued emails.

#### For Development (Windows):

Open a new PowerShell/Command Prompt window and run:

```bash
cd C:\xampp\htdocs\Henzo-Sushi
php artisan queue:work
```

Keep this window open while your application is running.

#### For Development (Mac/Linux):

```bash
cd /path/to/Henzo-Sushi
php artisan queue:work
```

### 4. Queue Worker Options

#### Process jobs continuously:
```bash
php artisan queue:work
```

#### Process jobs with delay (respects rate limits):
```bash
php artisan queue:work --sleep=1
```

#### Run in background (Windows):
Use a task scheduler or run it as a service.

#### Production:
Use a process manager like Supervisor (Linux) or run as a Windows service.

### 5. Monitor Queue Jobs

#### View failed jobs:
```bash
php artisan queue:failed
```

#### Retry failed jobs:
```bash
php artisan queue:retry all
```

#### Clear failed jobs:
```bash
php artisan queue:flush
```

## How It Works

1. **Creating a Promo Code with Email:**
   - When you create a promo code and check "Send email to all customers"
   - Jobs are queued (not sent immediately)
   - The page responds quickly with "X emails queued for delivery"

2. **Queue Worker Processing:**
   - The queue worker picks up jobs one by one
   - Sends emails with automatic retries on failure
   - Respects rate limits with delays between emails

3. **Benefits:**
   - ✅ No rate limiting errors
   - ✅ Fast page response (emails sent in background)
   - ✅ Automatic retries on failure
   - ✅ Can handle thousands of emails

## Troubleshooting

### Emails not sending?
1. Make sure the queue worker is running: `php artisan queue:work`
2. Check your `.env` file has `QUEUE_CONNECTION=database`
3. Check the logs: `storage/logs/laravel.log`

### Rate limit errors?
The queue worker will automatically retry failed jobs. Check `php artisan queue:failed` to see any failed jobs.

### Queue worker stops?
The queue worker will stop if it encounters an error or you close the terminal. Restart it with `php artisan queue:work`.

