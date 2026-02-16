#!/bin/bash

# FHIRTools Development Container Setup Script
set -e

echo "🚀 Setting up PHP FHIRTools development environment..."

# Make sure we're in the workspace directory
cd /workspace

# Install Composer dependencies if not already installed
if [ ! -d "vendor" ]; then
    echo "📦 Installing Composer dependencies..."
    composer install --no-interaction --optimize-autoloader
else
    echo "✅ Composer dependencies already installed"
fi

# Set up Git hooks directory (if using)
if [ -d ".git" ]; then
    echo "🔧 Configuring Git..."
    git config --local core.hooksPath .githooks 2>/dev/null || true
fi

# Create necessary directories
echo "📁 Creating necessary directories..."
mkdir -p output
mkdir -p var/cache
mkdir -p var/log
mkdir -p coverage

# Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 755 bin/console 2>/dev/null || true
chmod -R 777 var/ 2>/dev/null || true
chmod -R 777 coverage/ 2>/dev/null || true

# Verify PHP configuration
echo "🔍 Verifying PHP configuration..."
php -v
php -m | grep -E "(xdebug|zip|ctype|iconv|intl)" || echo "⚠️  Some extensions may be missing"

# Test Composer scripts
echo "🧪 Testing Composer scripts..."
composer run-script --list

# Verify Symfony console
echo "🎯 Testing Symfony console..."
php bin/console --version

# Run initial code quality checks
echo "🔍 Running initial code quality checks..."
if [ -f "vendor/bin/phpstan.phar" ]; then
    echo "Running PHPStan..."
    composer run phpstan || echo "⚠️  PHPStan found issues - check output above"
else
    echo "⚠️  PHPStan not found - run 'composer install' to install dev dependencies"
fi

if [ -f "vendor/bin/pint" ]; then
    echo "Running Pint (code style)..."
    composer run lint || echo "⚠️  Pint found style issues - check output above"
else
    echo "⚠️  Pint not found - run 'composer install' to install dev dependencies"
fi

# Display helpful information
echo ""
echo "🎉 Development environment setup complete!"
echo ""
echo "📋 Available commands:"
echo "  composer run test          - Run PHPUnit tests"
echo "  composer run test-coverage - Run tests with coverage"
echo "  composer run phpstan       - Run static analysis"
echo "  composer run lint          - Fix code style"
echo "  php bin/console list       - List available console commands"
echo "  php bin/console fhir:generate --help - FHIR generation help"
echo ""
echo "🐛 Debugging:"
echo "  - Xdebug is configured on port 9003"
echo "  - Set breakpoints in VS Code and start debugging"
echo "  - Use 'Listen for Xdebug' configuration"
echo ""
echo "📁 Important directories:"
echo "  - src/           - Source code"
echo "  - tests/         - Test files"
echo "  - output/        - Generated FHIR classes"
echo "  - coverage/      - Test coverage reports"
echo ""
echo "Happy coding! 🚀"