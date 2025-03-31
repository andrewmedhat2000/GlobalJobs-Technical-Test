# Task Management System API

## Overview
The **Task Management System API** is a RESTful API designed to manage tasks efficiently. It includes authentication, task creation, retrieval, updating, task dependencies, and role-based access control (RBAC).

## Features
- **User Authentication:** Implemented using Laravel Passport.
- **Task Management:** Create, update, and retrieve tasks with filtering options.
- **Task Dependencies:** Ensure tasks cannot be completed until dependencies are met.
- **Role-Based Access Control (RBAC):**
  - Managers can create, update, and assign tasks.
  - Users can retrieve only their assigned tasks and update task status.
- **API Documentation:** Postman collection included.

