#!/bin/bash

# Script to fix duplicate route name conflict
# Run this on your server after pulling the latest code

echo "Fixing duplicate route name conflict..."

# Path to the routes file
ROUTES_FILE="vendor/safiull/laravel-installer/src/Routes/web.php"

# Check if file exists
if [ ! -f "$ROUTES_FILE" ]; then
    echo "Error: File $ROUTES_FILE not found!"
    exit 1
fi

# Fix the route name conflict
sed -i "s/'as' => 'LaravelInstaller::'/'as' => 'LaravelVerifier::'/g" "$ROUTES_FILE"

# Verify the change
if grep -q "'as' => 'LaravelVerifier::'" "$ROUTES_FILE"; then
    echo "✓ Route name fixed successfully!"
else
    echo "✗ Failed to fix route name. Please check manually."
    exit 1
fi

# Clear Laravel caches
echo "Clearing Laravel caches..."
php artisan route:clear
php artisan config:clear
php artisan cache:clear

# Rebuild caches
echo "Rebuilding caches..."
php artisan route:cache
php artisan config:cache

echo "✓ All done! The route conflict has been resolved."

