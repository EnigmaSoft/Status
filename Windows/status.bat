:loop
cd C:\xampp\htdocs\Enigma\CMS
php artisan schedule:run > NUL 2>&1
goto loop