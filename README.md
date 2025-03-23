Technical Test for Back-end Developer

Project Setup and Documentation

A. Environment Setup

Docker Environment:

Spin-up a Docker environment to host the Laravel project.

Laravel Project:

Create a new Laravel project using the latest Laravel version.

API Documentation:

Create comprehensive API documentation using Postman.

Organize all APIs within a Postman workspace, providing descriptions, parameters, responses, and authentication details.

Mailhog Integration:

Set up Mailhog to handle email within your Laravel project (for local email testing).

Authentication and Job Seeker Features

B. Authentication & Job Seeker Features

Job Seeker Authentication:

Implement authentication using the Passport package.

Allow job seekers to register and login via the API.

Verification System:

Implement verification via mobile number or email.

Follow a three-step process: Sending, Verifying, and Finalizing the verification.

Multi-Method Login:

Allow users to login using a username, password, or mobile number.

Registration Notifications

C. Notifications

Event Dispatching:

Dispatch a notification event upon successful registration.

Database Storage & Email:

Store notification types in the database.

Send notifications via email (use Mailhog for testing).

User and Admin Functionality

D. User & Admin Functionality

Admin Registration & Authorization:

Enable admin registration.

Implement gates and policies for authorization.

Job Management:

Admins can add a list of available jobs.

Job seekers can apply for jobs.

Implement real-time WebSocket broadcasting for admins when a job application is submitted.

File Management:

Automatically delete uploaded files that are expired or don't have ownership.

E. Best Practices

Components & Structure:

Utilize various Laravel components:

Service Providers, Dependency Injection, Listeners, Events, Enums, Traits, Services, Rules, Policies, Gates, Jobs, Exceptions, Contracts, Resources, Requests, Middleware, Commands.

