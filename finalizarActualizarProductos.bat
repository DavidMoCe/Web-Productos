@echo off

REM Finalizar XAMPP
echo Finalizando XAMPP...
start "" /min cmd /c "cd /d C:\xampp && xampp_stop.exe"

REM Esperar un momento para que XAMPP se detenga completamente
timeout /t 5 /nobreak

REM Finalizar el proceso php artisan schedule:work
echo Finalizando el proceso php artisan schedule:work...

REM Encuentra el PID del proceso php artisan schedule:work
for /f "tokens=2 delims= " %%a in ('tasklist /fi "imagename eq php.exe" /v ^| findstr /i "php artisan schedule:work"') do set "PID=%%a"

REM Finaliza el proceso usando su PID
if defined PID (
    taskkill /f /pid %PID%
    echo Proceso php artisan schedule:work finalizado.
) else (
    echo No se encontró ningún proceso php artisan schedule:work en ejecución.
)

echo Tareas finalizadas.
