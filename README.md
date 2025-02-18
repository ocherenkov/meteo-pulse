# MeteoPulse

MeteoPulse is a Laravel-based application for managing user weather preferences, notifications, and cities. This guide
explains how to set up and run the project using Docker Compose.

---

## Requirements

- **Docker**: >= 20.10
- **Docker Compose**: >= 2.0

---

## Installation and Running the Project

1. **Clone the Repository**:

   ```bash
   git clone https://github.com/ocherenkov/meteo-pulse.git
   cd meteo-pulse
   ```

2. **Start the Application**:

   Run the following command to build and start the containers:

   ```bash
   docker-compose up -d
   ```

   The application will automatically initialize:
    - `.env` will be generated from `.env.example` if it doesn't exist.
    - Environment variables (DB, Redis, Mail) will be automatically passed to `.env`.
    - Dependencies will be installed.
    - Migrations and seeders will run automatically.

3. **Access the Application**:

   Once the containers are running, the application will be available.

---

## Useful Commands

- **Stop Containers**:

  ```bash
  docker-compose down
  ```

  This stops and removes all running containers. Persistent data (like the database) will not be deleted because it is
  stored in volumes.

- **Running Migrations Manually** (if needed):

  ```bash
  docker-compose exec app php artisan migrate
  ```

- **Access the Application Container**:

  If you need to debug or execute commands manually inside the application container, use:

  ```bash
  docker-compose exec app bash
  ```

- **Clear Cache**:

  If you need to refresh caches (config, routes, etc.), run this inside the container:

  ```bash
  docker-compose exec app php artisan cache:clear
  ```

---

## Testing Emails

To test email functionality, the project uses **MailHog**. You can access its web interface at:

[http://localhost:8025](http://localhost:8025)

---

## Running Tests

Execute the following command inside the application container:

   ```bash
   docker-compose exec app php artisan test
   ```

---

## Troubleshooting

If you encounter issues (e.g., the project doesn't load properly), try the following:

1. Ensure Docker containers are running:

   ```bash
   docker-compose ps
   ```

2. Check container logs for errors:

   ```bash
   docker-compose logs app
   ```

3. Rebuild the containers (if changes were made to Docker configs):

   ```bash
   docker-compose up --build -d
   ```

---

MeteoPulse should now be up and running smoothly with no extra configuration beyond starting the Docker containers!
