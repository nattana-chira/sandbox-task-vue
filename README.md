## Getting Started  

```bash
cp .env.example .env
npm install
composer install

php artisan serve # no need if run on docker or xampp
npm run dev
```

** note ** 
vue will try to send api back to localhost port 80  
app might failed if backend api run on another port
