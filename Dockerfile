FROM php:7.4-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy source code into the container
COPY src/ /var/www/html/

# Create the uploads directory and set permissions
RUN mkdir -p /var/www/html/uploads && \
    chmod -R 755 /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html/uploads

# Copy encoded flag to a hidden location
COPY secret_flag.txt /var/hidden/secret_flag.txt
RUN chmod 644 /var/hidden/secret_flag.txt

# Expose port 80
EXPOSE 80