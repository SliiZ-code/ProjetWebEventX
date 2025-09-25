# Client PHP avec Twig
FROM php:8.2-cli

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Permettre à Composer de s'exécuter en tant que root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql

# Définir le répertoire de travail
WORKDIR /app

# Copier les fichiers client
COPY . .

# Installer les dépendances via Composer
RUN composer install --no-dev --optimize-autoloader

# Port exposé
EXPOSE 3000

# Démarrer le serveur PHP intégré
CMD ["php", "-S", "0.0.0.0:3000", "-t", "/app"]