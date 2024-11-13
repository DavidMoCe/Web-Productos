a@echo off

REM Inicia XAMPP
echo Iniciando XAMPP, Apache y Mysql...
start /B "" "C:\xampp\xampp_start.exe"

REM Espera a que XAMPP se inicie completamente
echo Esperando a que XAMPP se inicie completamente...
timeout /t 5 /nobreak > nul

REM Espera a que Apache se inicie completamente
echo Esperando a que Apache se inicie completamente...
timeout /t 2 /nobreak > nul

REM Espera a que MySQL se inicie completamente
echo Esperando a que MySQL se inicie completamente...
timeout /t 2 /nobreak > nul

REM Ejecuta el comando php artisan schedule:work
echo Ejecutando php artisan schedule:work...
start /B "" cmd /c "cd /d C:\xampp\htdocs\carpeta && php artisan schedule:work > nul 2>&1"
