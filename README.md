# Project: User Registration and Login System with Profile Management and Email Verification

## Overview
This repository contains the source code for a **pure MVC web application** that provides user registration, login, profile management, and token-based email verification. The application also sends emails during account registration and deletion. Everything is built from scratch, with no external packages or libraries.

## Features
- User Registration: Allows users to register by providing a firstname, lastname, email, and password.
- Login: Users can log in with their registered email and password.
- Email Verification: Token-based email verification to confirm the user's email address after registration.
- Profile Management: Logged-in users can update their profile information, such as email.
- Email Notifications: Sends an email upon registration and another when an account is deleted.

## Installation
To get the application running you PHP, MySQL, Apache2, and a mail server. 

#### 1. Clone the repository:
```
https://github.com/siyabongakonile/RegistrationAndLoginSystem.git
```

#### 2. Point Apache to the Root directory
You can do this by using virtual hosts (Follow this tutorial to learn how to create vhosts).

#### 3. Setup your Environment
- Got to the `\inc` directory and rename `config.example.php` to `config.php`.
- Uncomment the defined constants in the file.
- Add the necessary database information for your system.


## Usage
- **Registration**: Navigate to `/register` and fill out the registration form.
- **Login**: Navigate to `/login` and fill the login form.
- **Profile Management**: Access your profile page at `/profile` and edit it at `/profile/edit`.
- **Email Verification**: After registering check you emails. You should get 2 emails, one celebrating your registration and the other giving you a verification link. You can also verify your email by logging in your profile and click on the verify email link and a new email will be sent with the verification link.
- **Account Deletion**: Users can delete their account, triggering a confirmation email for deletion.

## Directory Structure
- **/App**: Contains the core logic for routing, sessions, authentication, email handling, and profile management.
- **/App/Controller**: Contains the controllers responsible for handling requests and communicating between models and views.
- **/App/Model**: Contains the models representing the data and business logic of the application.
- **/inc**: Contains all files needed to bootstrap the application.
- **/views**: ontains all the view files (templates) used to render HTML responses.

## Contributing
Feel free to contribute by submitting issues or pull requests.