# 🚀 Sharing Your Project on GitHub

Follow these steps to upload your project and let your friend use it.

## 1. Prepare Your Repository

1.  **Create a New Repo**: Go to [GitHub](https://github.com/new) and create a new repository called `E-commerce-site`.
2.  **Keep it Private/Public**: Choose according to your preference.
3.  **Do NOT** initialize with a README (since we already have one).

## 2. Upload from Your Device

Open your terminal in the project folder and run:

```bash
git init
git add .
git commit -m "Initial commit - Mahly Platform"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/E-commerce-site.git
git push -u origin main
```

## 3. What Your Friend Needs to Do

Give your friend the link to your repository. They should:

1.  **Clone the project**: `git clone <your-repo-link>`
2.  **Install PHP Packages**: `composer install`
3.  **Install PHP Packages**: `composer require laravel/socialite`
4.  **Install JS Packages**: `npm install`
5.  **Setup Environment**:
    - Copy `.env.example` to a new file named [.env](file:../E-commerce-site/.env).
    - **Crucial**: They must enter their own Database name/password in this new [.env](file:../E-commerce-site/.env) file.
    - Enter the google API information
        - GOOGLE_CLIENT_ID=YOUR-client_id
        - GOOGLE_CLIENT_SECRET=YOUR-client_secert
        - GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
6.  **Generate Key**: `php artisan key:generate`
7.  **Run Database**: `php artisan migrate`
8.  **Run Project**: `php artisan serve` and `npm run dev` in a second terminal.

> [!IMPORTANT]
> Since [.env](file:///c:/AntiGravity/E-commerce-site/.env) is ignored by Git (for security), your friend **must** create their own [.env](file:///c:/AntiGravity/E-commerce-site/.env) file and set up their local database before the project will work on their machine.
