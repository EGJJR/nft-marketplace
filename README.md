# NFT Marketplace

A web-based NFT marketplace that allows users to browse, upload, and purchase NFTs (simulated, no blockchain integration).

## Features

- User authentication (register/login/logout)
- Browse NFTs on the public marketplace
- Upload and manage NFTs
- Purchase NFTs
- Responsive design using Bootstrap
- Secure password handling
- Image upload functionality
- Transaction history

## Project Structure

```
/nft_marketplace
│── /assets
│   ├── /css
│   │   └── style.css
│   ├── /js
│   │   └── main.js
│   └── /images
│       └── /nfts
│── /db
│   └── db_config.php
│── /auth
│   ├── register.php
│   ├── login.php
│   └── logout.php
│── /members
│   ├── dashboard.php
│   ├── upload_nft.php
│   ├── edit_nft.php
│   ├── delete_nft.php
│   └── purchase.php
│── /public
│   └── home.php
│── /includes
│   ├── header.php
│   └── footer.php
│── index.php
└── README.md
```

## Database Schema

### Users Table
```sql
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### NFTs Table
```sql
CREATE TABLE nfts (
    nft_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);
```

### Transactions Table
```sql
CREATE TABLE transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    nft_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (nft_id) REFERENCES nfts(nft_id) ON DELETE CASCADE
);
```

## Setup Instructions

1. Create a MySQL database named `nft_marketplace`
2. Import the database schema from the provided SQL file
3. Configure the database connection in `db/db_config.php`
4. Ensure the `assets/images/nfts` directory has write permissions
5. Set up a web server (Apache/Nginx) with PHP support
6. Place the project files in your web server's document root

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- GD Library for image processing
- PDO PHP extension
- mod_rewrite enabled (for Apache)

## Security Features

- Password hashing using PHP's `password_hash()`
- Prepared statements for all database queries
- Input validation and sanitization
- Session-based authentication
- XSS prevention through proper escaping
- CSRF protection (to be implemented)

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request