#!/bin/bash
# Script para configurar el entorno de desarrollo de Fisiopask
# Uso: source setup-env.sh

# Configurar PHP 8.1
export PATH="/opt/homebrew/opt/php@8.1/bin:$PATH"
export PATH="/opt/homebrew/opt/php@8.1/sbin:$PATH"

# Cargar nvm y usar la versión del proyecto
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
nvm use

echo ""
echo "✅ Entorno configurado para Fisiopask:"
echo "   PHP: $(php -v | head -n 1)"
echo "   Node: $(node -v)"
echo "   NPM: $(npm -v)"
echo ""
