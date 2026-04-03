FROM php:8.2-apache

# 1. 安装系统依赖并安装PHP扩展(mysqli,pdo_mysql)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-enable mysqli

# 2. 启用Apache的rewrite模块
RUN a2enmod rewrite

# 3. 复制项目文件到容器中
COPY . /var/www/html/

# 4. 设置apache权限
RUN chown -R www-data:www-data /var/www/html

# 5. 暴露端口
EXPOSE 80
