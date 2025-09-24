# Image de base avec PHP et Apache
FROM php:8.2-apache

# Installation des extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Activation du module rewrite d'Apache
RUN a2enmod rewrite

# Copie des fichiers du serveur
COPY . /var/www/html/

# Configuration des permissions
RUN chown -R www-data:www-data /var/www/html/
RUN chmod -R 755 /var/www/html/

# Création du dossier uploads
RUN mkdir -p /var/www/html/uploads && chown -R www-data:www-data /var/www/html/uploads

# Port exposé
EXPOSE 80

# Configuration Apache pour les API
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Commande par défaut
CMD ["apache2-foreground"]