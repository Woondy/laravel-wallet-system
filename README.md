## Wallet System

A Laravel-based wallet management system for handling deposits, withdrawals, and rebate calculations asynchronously.

## Introduction

The Wallet System is designed to supports concurrent deposit and withdrawal operations. Each deposit should trigger a rebate calculation job, which calculates a 1% rebate and credits it to the wallet asynchronously. Ensure the system handles multiple requests to update the same wallet accurately.

## System Requirements
    - PHP >= 8.3
    - Composer
    - MySQL, MariaDB or SQLite (it's important to ensure that the database timezone is configured to UTC)

## Installation

To install and run the Wallet System locally, follow these steps:

1. Clone the repository:
    ```bash
    git clone https://github.com/Woondy/laravel-wallet-system.git
    cd laravel-wallet-system
    ```

2. Install dependencies:
    ```bash
    composer install
    ```

3. Copy .env.example to .env and configure your environment variables:
    ```bash
    cp .env.example .env
    ```

4. Generate application key:
    ```bash
    php artisan key:generate
    ```

5. Create a database and update .env with your database credentials.

6. Run migrations and seed the database:
    ```bash
    php artisan migrate --seed
    ```

7. Start the development server:
    ```bash
    php artisan serve
    ```

## Usage

### Basic Operations
    - Deposit: deposit funds into a wallet and rebate calculations asynchronously.
    - Withdraw: withdraw funds from a wallet, ensuring no overdraft.
    - Balance: retrieve current wallet balance and transaction history.


### To run unit tests
```bash
php artisan test
```

### API Endpoints
    - POST /api/wallets/{id}/deposit: Deposit funds into a wallet.
    - POST /api/wallets/{id}/withdraw: Withdraw funds from a wallet.
    - GET /api/wallets/{id}/balance: Retrieve wallet balance and transaction history for a wallet.
