# **Email Parser API**

This is a Laravel-based backend application that:
- Parses raw email content and extracts the plain text body.
- Stores the parsed emails in a MySQL database.
- Provides a REST API with authentication for managing email records.
- Includes a scheduled job to process new emails every hour.

## **Table of Contents**
- [Installation](#installation)
- [Database Setup](#database-setup)
- [Running the Application](#running-the-application)
- [Parsing Emails](#parsing-emails)
- [Alternative: Using cursor()](#alternative-using-cursor)
- [Choosing Between chunk() and cursor()](#choosing-between-chunk-and-cursor)
- [Authentication](#authentication)
- [API Endpoints](#api-endpoints)
- [Deployment Instructions](#deployment-instructions)
- [Testing](#testing)
- [Contact](#contact)

---

## **Installation**

To set up this project, ensure you have **PHP 8+, MySQL, Composer, and Laravel** installed.

### **Step 1: Clone the repository**
```bash
git clone https://github.com/azolee/email-parser.git
cd email-parser
```

### **Step 2: Install dependencies**
```bash
composer install
```

### **Step 3: Copy environment variables**
```bash
cp .env.example .env
```
Edit the `.env` file to configure your **database connection**.

---

## **Database Setup**

### **Step 4: Set up the database**
Update `.env` with your MySQL database credentials:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=email_parser
DB_USERNAME=root
DB_PASSWORD=yourpassword
```

### **Step 5: Run migrations**
```bash
php artisan migrate
```

### **Step 6: Seed the database (optional)**
```bash
php artisan db:seed
```

---

## **Running the Application**

### **Start the local server**
```bash
php artisan serve
```
The API will be available at:
```
http://127.0.0.1:8000
```

### **Schedule the parsing command**
Laravel's scheduler needs to run in the background. You can test the email parsing manually with:
```bash
php artisan emails:parse
```

To automate it every hour, add the following to **cron jobs**:
```bash
crontab -e
```
Add:
```bash
0 * * * * cd /path/to/email-parser && php artisan schedule:run >> /dev/null 2>&1
```

---

## **Parsing Emails**
This application extracts plain text from raw email content and updates the `successful_emails` table.

- Only **unprocessed emails** are parsed.
- The result is stored in the `raw_text` column.
- by default, 50 emails are processed in a batch.
- if an error occurs, the email is skipped and the process continues.
- the parsing process can be triggered setting the `size` parameter of the command to one, this way the `cursor()` is used.

To manually trigger parsing:
```bash
php artisan emails:parse
```

### **Chunk-based Processing**
The emails are processed in **batches of 50 by default** to **optimize memory usage**:
```php
$size = $this->argument('size');
SuccessfulEmail::whereNull('raw_text')->chunk($size, function ($emails) {
    foreach ($emails as $email) {
        try {
            $plainText = $this->extractPlainText($email->email);
            $email->update(['raw_text' => $plainText]);
            $this->info("Parsed email ID: {$email->id}");
        } catch (\Exception $e) {
            $this->error("Failed to parse email ID: {$email->id}. Error: " . $e->getMessage());
        }
    }
});
```

### **Using `cursor()` with Command Argument**
If the command is used with `php artisan emails:parse 1`, it will use `cursor()` instead of `chunk()`:
```php
SuccessfulEmail::whereNull('raw_text')->cursor()->each(function ($email) {
    $plainText = $this->extractPlainText($email->email);
    $email->update(['raw_text' => $plainText]);
    $this->info("Parsed email ID: {$email->id}");
});
```

---

## **Choosing Between `chunk()` and `cursor()`**
| Feature  | `chunk($size, function ($emails) { ... })` | `cursor()->each(function ($email) { ... })` |
|----------|--------------------------------------------|--------------------------------|
| **Memory Usage** | Medium (keeps 50 items in memory)          | Low (keeps only 1 item in memory) |
| **Speed** | Faster (batch updates)                     | Slower (single record updates) |
| **Failure Recovery** | Can restart from last batch                | Continues from last successful record |
| **Best For** | Medium to large datasets                   | Huge datasets (millions of records) |

**Recommendation:** For most cases, **use `chunk()`** since it is both **efficient and fast**.

---

## **Authentication**

This API uses **Laravel Sanctum** for authentication.

### **Generate API Token**
1. Register or log in a user.
2. Generate an API token:
   ```bash
   php artisan tinker
   ```
   ```php
   $user = App\Models\User::first();
   $token = $user->createToken('API Token')->plainTextToken;
   echo $token;
   ```
3. Use the token for API requests by adding it to the `Authorization` header:
   ```
   Authorization: Bearer YOUR_ACCESS_TOKEN
   ```

---

## **API Endpoints**

All endpoints require **authentication** with a Bearer Token.

| Method | Endpoint | Description |
|--------|---------|-------------|
| `POST` | `/api/emails` | Create a new email record (automatically parses it). |
| `GET`  | `/api/emails` | Fetch all emails (excluding deleted). |
| `GET`  | `/api/emails/{id}` | Get a specific email by ID. |
| `PUT`  | `/api/emails/{id}` | Update an email record. |
| `DELETE` | `/api/emails/{id}` | Soft delete an email. |

---

## **Deployment Instructions**

### **Step 1: Upload project to the server**
```bash
scp -r email-parser user@server:/var/www/email-parser
```

### **Step 2: Configure Environment**
On the server, update `.env`:
```ini
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### **Step 3: Install Dependencies**
```bash
composer install --optimize-autoloader --no-dev
```

### **Step 4: Run Migrations**
```bash
php artisan migrate --force
```

### **Step 5: Set File Permissions**
```bash
chmod -R 775 storage bootstrap/cache
```

### **Step 6: Set Up Supervisor for Parsing**
Create a Supervisor config at `/etc/supervisor/conf.d/email_parser.conf`:
```
[program:email_parser]
command=php /var/www/email-parser/artisan schedule:run
autostart=true
autorestart=true
stderr_logfile=/var/log/email_parser.err.log
stdout_logfile=/var/log/email_parser.out.log
```
Reload Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start email_parser
```

### **Step 7: Start Laravel**
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

---

## **Testing**
Run Laravel tests:
```bash
php artisan test
```

---

## **Contact**
For any questions, contact:
- **Andras Zoltan** (`azolee@gmail.com`)
