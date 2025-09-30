# Cardinal Voting Application

Cardinal is a secure and transparent corporate voting platform built with the Laravel framework. It empowers organizations to manage Annual General Meetings (AGMs) and allows shareholders to participate in voting procedures in a modern, reliable, and accessible way.

## Features

### Admin Portal

-   **Dashboard**: An overview of system activities.
-   **Company Management**: Full CRUD (Create, Read, Update, Delete) for companies.
-   **Shareholder Management**: Manage shareholder information and their holdings.
-   **AGM Management**: Create and manage AGMs, including details, descriptions, and scheduling.
-   **Voting Item Management**: Add, edit, and manage specific voting items (resolutions) for each AGM, with support for "Yes/No" and "Multiple Choice" questions.
-   **Voting Control**: Manually close individual voting items or an entire AGM. Closing a session automatically triggers result notifications.
-   **Automated Notifications**:
    -   Event-driven email notifications to shareholders when an AGM is created.
    -   Event-driven email notifications with voting results when a voting session is closed.
    -   Scheduled email reminders sent to shareholders one hour before an AGM begins.
-   **Reporting**: Generate and view reports on AGM results.

### Shareholder Portal

-   **Personalized Dashboard**: A clean interface showing recent, active, and upcoming AGMs relevant to the shareholder.
-   **AGM Details**: View comprehensive details for each AGM, including a list of all voting items.
-   **Secure Voting**: A dedicated page to cast votes on active resolutions. The system prevents duplicate voting.
-   **Email Notifications**: Receive timely emails about new AGMs, reminders for upcoming meetings, and the final results of voting sessions.

## Tech Stack

-   **Backend**: PHP 8+, Laravel 10
-   **Frontend**: Bootstrap 5, Blade
-   **Database**: MySQL
-   **Task Scheduling**: Laravel Scheduler (Cron)
-   **Background Jobs**: Laravel Queues

---

## Getting Started

Follow these instructions to get a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

-   PHP >= 8.1
-   Composer
-   Node.js & NPM
-   A database server (e.g., MySQL)

### Installation

1.  **Clone the repository:**

    ```bash
    git clone https://github.com/femoxmed/voting-app.git
    cd cardinal-voting-app
    ```

2.  **Install PHP dependencies:**

    ```bash
    composer install
    ```

3.  **Install front-end dependencies and compile assets:**

    ```bash
    npm install
    npm run dev
    ```

4.  **Set up your environment file:**
    Copy the example environment file and generate an application key.

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5.  **Configure your `.env` file:**
    Open the `.env` file and update the following sections with your local environment's details:

    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=cardinal_voting_app_db
    DB_USERNAME=root
    DB_PASSWORD=

    MAIL_MAILER=smtp
    MAIL_HOST=mailpit
    MAIL_PORT=1025
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS="hello@example.com"
    MAIL_FROM_NAME="${APP_NAME}"
    ```

6.  **Run database migrations and seeders:**
    This will create the necessary tables and populate them with initial data (if seeders are configured).

    ```bash
    php artisan migrate --seed
    ```

7.  **Run the development server:**
    ```bash
    php artisan serve
    ```
    The application will be available at `http://127.0.0.1:8000`.

### Background Processes

This application relies on background processes for sending notifications and reminders.

1.  **Scheduler for Reminders:**
    To send AGM reminders, the Laravel scheduler must be run every minute. Add the following Cron entry to your server.

    ```bash
    * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
    ```

2.  **Queue Worker for Notifications:**
    Event-driven notifications (like voting results) are handled by a queue worker. To process these jobs, run the following command:
    ```bash
    php artisan queue:work
    ```

---

## Project Structure and Conventions

This project follows the standard Laravel directory structure and PSR-4 autoloading conventions. Adhering to these conventions is critical for the application to function correctly, as Laravel's service container and autoloader rely on classes being in their expected locations.

### Directory Guide

Placing files in the wrong directory can lead to "class not found" errors or other unexpected behavior (like `Call to a member function on array` when dispatching events). Please ensure your classes are located in the correct directories:

| Class Type        | Directory               | Example                                        |
| ----------------- | ----------------------- | ---------------------------------------------- |
| **Controllers**   | `app/Http/Controllers/` | `app/Http/Controllers/Admin/AgmController.php` |
| **Models**        | `app/Models/`           | `app/Models/Agm.php`                           |
| **Events**        | `app/Events/`           | `app/Events/AgmClosed.php`                     |
| **Listeners**     | `app/Listeners/`        | `app/Listeners/SendAgmResultNotifications.php` |
| **Jobs**          | `app/Jobs/`             | `app/Jobs/SendAgmCreatedNotificationJob.php`   |
| **Notifications** | `app/Notifications/`    | `app/Notifications/VoteCastNotification.php`   |
| **Services**      | `app/Services/`         | `app/Services/AgmService.php`                  |
| **Providers**     | `app/Providers/`        | `app/Providers/EventServiceProvider.php`       |
| **Middleware**    | `app/Http/Middleware/`  | `app/Http/Middleware/AdminMiddleware.php`      |

### Troubleshooting Autoloading Issues

If you encounter strange errors after creating or moving files, it's often a sign of an autoloading issue. Here are some steps to resolve them:

1.  **Check Namespaces and Locations**: Ensure the `namespace` at the top of your file matches its directory path according to PSR-4.
2.  **Delete Duplicates**: Make sure you do not have duplicate class files in different directories.
3.  **Refresh the Autoloader**: Run the following command to regenerate Composer's optimized autoloader map. This forces it to re-scan your project for classes.

    ```bash
    composer dump-autoload
    ```

---

## License

This project is open-sourced software licensed under the MIT license.
