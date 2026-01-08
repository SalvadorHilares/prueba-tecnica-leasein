@echo off
echo ========================================
echo   LeaseIn - Iniciando proyecto
echo ========================================
echo.

echo [1/5] Levantando contenedores...
docker-compose up -d --build
if %errorlevel% neq 0 (
    echo ERROR: No se pudieron levantar los contenedores
    pause
    exit /b 1
)
timeout /t 5 /nobreak >nul

echo [2/5] Instalando dependencias de Composer...
docker-compose exec -T backend composer install
if %errorlevel% neq 0 (
    echo ERROR: No se pudieron instalar las dependencias
    pause
    exit /b 1
)

echo [3/5] Configurando .env...
docker-compose exec -T backend bash -c "if [ ! -f .env ]; then echo 'APP_NAME=Laravel' > .env && echo 'APP_ENV=local' >> .env && echo 'APP_KEY=' >> .env && echo 'APP_DEBUG=true' >> .env && echo 'APP_URL=http://localhost:8000' >> .env && echo '' >> .env && echo 'DB_CONNECTION=pgsql' >> .env && echo 'DB_HOST=db' >> .env && echo 'DB_PORT=5432' >> .env && echo 'DB_DATABASE=leasein_db' >> .env && echo 'DB_USERNAME=leasein_user' >> .env && echo 'DB_PASSWORD=leasein_password' >> .env; fi"

echo [4/5] Generando clave y migraciones...
docker-compose exec -T backend php artisan key:generate --force
docker-compose exec -T backend php artisan migrate --force
docker-compose exec -T backend php artisan db:seed

echo [5/5] Iniciando servidor backend...
docker-compose exec -d backend php artisan serve --host=0.0.0.0 --port=8000
timeout /t 3 /nobreak >nul

echo.
echo ========================================
echo   LISTO!
echo ========================================
echo.
echo Frontend: http://localhost
echo Backend API: http://localhost:8000/api/equipos
echo.
echo Para detener: docker-compose down
echo.
pause
