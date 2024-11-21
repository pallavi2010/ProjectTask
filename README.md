# ProjectTask

## Description
ProjectTask is a Laravel-based application for managing projects and their associated tasks. It includes features like task creation, editing, deletion, and drag-and-drop task reordering.

---

## Prerequisites
Before setting up the project, ensure the following are installed on your local machine:
- PHP (>= 7.4)
- Composer
- MySQL or any other supported database
- Node.js and npm (optional, for frontend assets)

---

## Installation Guide

### 1. Clone the Repository
Clone the repository to your local machine using the following command:
git clone https://github.com/pallavi2010/ProjectTask.git



Navigate to the project folder 
cd ProjectTask


Install all the PHP dependencies using Composer:
composer install


Create the environment configuration file by copying the example file
cp .env.example .env

Update the .env file with your database credentials

Generate the application key to secure your application
php artisan key:generate

Set up the database tables by running the migrations
php artisan migrate

Run the seeder to populate projects
php artisan db:seed --class=ProjectSeeder  

Run the Laravel development server to test the application
php artisan serve


