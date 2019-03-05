# PHPTokenAuth
This application was developed to offer an educational quick-start into how token authentication can be used for persistent login.

## Application details
- Developed with vanilla PHP (no external libraries)
- Uses PDO for interacting with a relational SQL database
- Secure authentication with salted and hashed credentials
- `Selector:Validator` structure used for persistent login with a `remember me` checkbox
- Uses Bootstrap CSS library

## Usage
To use the application, a few things must be set up. Firstly, a configuration file is require to create a PDO object that connects
to the database:

### config.php
```php
<?php

DEFINE('DB_HOST', 'localhost');
DEFINE('DB_NAME', 'token_auth');

DEFINE('DB_USER', '<your user>');
DEFINE('DB_PASS', '<your pass>');

try{
	$dbh = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';', DB_USER, DB_PASS);
} catch(PDOException $e){
	exit('Error connecting to database.');
}

$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
```

**Note:** Never make this file publicly accessible, do not include it in the same directory as your project, this poses a major
security risk.

After this file is created, simply insert the directory path into `includes/header.php`

## Database Structure
The database structure is fairly simple. The database is named `token_auth` and contains a two tables named `users` and `auth_tokens`.

## The users table is constructed of the following columns:
- username varchar(40)
- email varchar(40)
- fullname varchar(60)
- password varchar(255)
- id int(10) unsigned (auto incremented primary key)

```SQL
CREATE TABLE users(
    username VARCHAR(40) NOT NULL,
    email VARCHAR(40) NOT NULL,
    fullname VARCHAR(60) NOT NULL,
    password VARCHAR(255) NOT NULL,
    id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY
);
```

## The auth_tokens table has the following structure:
- token varchar(255)
- selector varchar(255)
- user_id int(10) unsigned (foreign key to users table)
- id int(10) unsigned (auto incremented primary key)

```SQL
CREATE TABLE auth_tokens(
    token VARCHAR(255) NOT NULL,
    selector VARCHAR(255) NOT NULL,
    user_id INT(10) UNSIGNED,
    id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    FOREIGN KEY(user_id) REFERENCES users(id)
);
```
