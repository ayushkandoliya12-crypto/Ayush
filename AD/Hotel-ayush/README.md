# Ayush Hotel Project - Beginner Guide

This project is a simple hotel booking website built with PHP, MySQL, and Bootstrap.

## 1. Project Flow

The website works like this:

1. User opens the website
2. User registers or logs in
3. User sees available rooms
4. User selects room and booking dates
5. System calculates total payment
6. Booking and payment record are saved in database
7. User can check booking/payment history

## 2. Main Files

- `index.php` - Home page
- `header.php` - Shared navigation and database setup
- `rooms.php` - Room listing and booking logic
- `login.php` - User login page
- `register.php` - New user registration page
- `account_history.php` - Shows booking and payment history
- `more.php` - Extra service/information page
- `footer.php` - Footer section used by all pages

## 3. Database Tables

The project uses MySQL tables like:

- `form` - stores users
- `rooms` - stores room details
- `bookings` - stores reservation details
- `payments` - stores payment information

## 4. Beginner Tips

### Session
A session keeps login details such as:
- user_id
- name
- role

This tells the website who is logged in.

### Form Submission
When a user submits form data, PHP reads values using:

```php
$_POST['name'];
$_POST['email'];
$_POST['password'];
```

### Password Security
Passwords are not stored in plain text. They are hashed with:

```php
password_hash($password, PASSWORD_DEFAULT)
```

And verified with:

```php
password_verify($password, $row['password'])
```

### Booking Calculation
The total payment is calculated like:

```php
$total_amount = $number_of_nights * $room_price;
```

## 5. Easy Learning Order

For a fresher, study in this order:

1. `header.php`
2. `register.php`
3. `login.php`
4. `rooms.php`
5. `account_history.php`
6. `index.php`

## 6. How to Run

1. Start XAMPP
2. Start Apache and MySQL
3. Open browser
4. Visit: `http://localhost/Ayush/p1/project/index.php`

## 7. Important Note

This project is a learning project. It is meant to help beginners understand:
- PHP basics
- MySQL database operations
- sessions and login/logout
- forms and validation
- room booking flow
- payment and history tracking

---

If you want, I can next make the code even more beginner-style by:
1. adding comments inside every PHP section,
2. renaming variables to simpler names,
3. rewriting the logic in a step-by-step style.
