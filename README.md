# Task Management System

A Laravel-based Task Management System that allows users to manage tasks with various roles and permissions. The system uses roles like Admin, Manager, and User, each with different permissions. It supports creating, assigning, updating, and deleting tasks, as well as handling soft deletes, task priorities, and date formatting. Tasks can be filtered by priority and status, and users can only update the status of tasks assigned to them.

## Features

- Role-based permissions:
   - Admins have full control (create, update, assign, delete tasks).
   - Managers can assign tasks and manage their own.
   - Users can update the status of tasks assigned to them.
- Task Assignment: Admins and Managers can assign tasks to specific users.
- Task Prioritization: Tasks have a priority field with three levels: `low`, `medium`, and `high`.
- Date Handling: Tasks have due dates (`due_date`), creation dates (`created_on`), and update dates (`updated_on`) formatted as `d-m-Y H:i`.
- Soft Deletes: Tasks and users can be recovered after deletion.
- Query Filters: Filter tasks by `priority` and `status` using query scopes.

## Technologies Used

- PHP 8.x
- Laravel 10.x
- MySQL (or other relational database)
- Spatie Laravel-Permission for role and permission management
- Postman (for API testing)
- Carbon for date formatting

## Installation

1. **Clone the Repository:**
```
https://github.com/amalSheikhdaher/Task_Management_System.git
```

2. **Install Dependencies:**
```
composer install
```

3. **Set up the environment:**

   Copy the `.env.example` file and configure the database settings and other environment variables.
```
cp .env.example .env
php artisan key:generate
```

4. **Set up the database:**

   Ensure your database configuration is correct in the `.env` file, then run the migrations:
```
 php artisan migrate
```

   If you are using Spatie Roles and Permissions, run the following command to cache the permissions:
   
```
php artisan permission:cache-reset
```

5. **Seed the database:**

   Create admin, manager, and user roles, and assign them to users by running the seeders.

```
php artisan db:seed
```

6. Serve the application

```
php artisan serve
```

Your application will be accessible at `http://localhost:8000`.


## API Endpoints

### Authentication


- POST `/api/login`: Authenticate and get a token.

### Tasks
- GET `/api/tasks`: List all tasks (Admins & Managers).
- POST `/api/tasks`: Create a new task (Admin & Manager).
- PUT `/api/tasks/{id}`: Update a task (Admin & Manager).
- DELETE `/api/tasks/{id}`: Soft delete a task (Admin).
- PUT `/api/tasks/{id}/status`: Update task status (Assigned user only).

### Request and Response Format

### Task Example

### POST `/api/tasks`

```
{
  "title": "Write a technical report",
  "description": "A report on AI advancements.",
  "priority": "high",
  "due_date": "2024-10-10 09:00",
  "assigned_to": 3
}
```

### Response

```
{
  "status": "success",
  "task": {
    "task_id": 1,
    "title": "Write a technical report",
    "description": "A report on AI advancements.",
    "priority": "high",
    "due_date": "10-10-2024 09:00",
    "status": "pending",
    "assigned_to": 3,
    "created_on": "09-09-2024 13:16",
    "updated_on": "09-09-2024 13:16"
  }
}
```

## Testing

To test the API, use Postman or any API client. Make sure you include the required authentication tokens in the request headers where necessary.

### Import Postman Collection:

   - Import the collection (https://www.postman.com/cloudy-eclipse-506985/workspace/task-management-system/collection/34376611-715cf1a6-7542-4bc7-9b2e-498012e1510a?action=share&creator=34376611) into Postman.

### Example Postman Test for Task Assignment

POST `/api/tasks/assign`

```
{
  "task_id": 1,
  "assigned_to": 2
}
```
Make sure the user has permission to assign tasks.

## Customization

- Date Formats: Customize the date formats by modifying the date accessors and mutators in the `Task` model.

- Role Management: The Spatie roles and permissions package can be customized by editing the permissions in the `RoleSeeder`.

## Future Enhancements

- Add email notifications for task assignments.
- Implement full-text search for tasks.
- Add support for file attachments to tasks.

## License

This project is licensed under the [MIT License](LICENSE).
