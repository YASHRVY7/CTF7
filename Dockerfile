FROM php:7.4-apache

# Install additional security packages
RUN apt-get update && apt-get install -y \
    libapache2-mod-security2 \
    && rm -rf /var/lib/apt/lists/*

# Copy source files to web root
COPY src/ /var/www/html/

# Create nested directories for the flag
RUN mkdir -p /var/hidden/level1/level2

# Create and set permissions for uploads directory
RUN mkdir -p /var/www/html/uploads && \
    chmod 777 /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html

# Copy encoded flag to a hidden location with multiple layers
COPY secret_flag.txt /var/hidden/level1/level2/secret_flag.txt
RUN chmod 644 /var/hidden/level1/level2/secret_flag.txt && \
    chown -R www-data:www-data /var/hidden

# Enable directory listing only for uploads
RUN echo "Options +Indexes" > /var/www/html/uploads/.htaccess

# Configure PHP execution for specific extensions
RUN echo "<FilesMatch \"\.(php|php\.).*$\">\nSetHandler application/x-httpd-php\n</FilesMatch>" >> /var/www/html/.htaccess

# Add security headers
RUN echo "ServerTokens Prod" >> /etc/apache2/apache2.conf && \
    echo "ServerSignature Off" >> /etc/apache2/apache2.conf

# Enable mod_security
RUN a2enmod security2

EXPOSE 80