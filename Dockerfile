FROM php:7.4-apache

# Copy source files to web root
COPY src/ /var/www/html/

# Create and set permissions for uploads directory
RUN mkdir -p /var/www/html/uploads && \
    chmod -R 777 /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html

# Copy encoded flag to a hidden location
COPY secret_flag.txt /var/hidden/secret_flag.txt
RUN chmod 644 /var/hidden/secret_flag.txt

# Enable directory listing for uploads
RUN echo "Options +Indexes" > /var/www/html/uploads/.htaccess

# Expose port 80
EXPOSE 80