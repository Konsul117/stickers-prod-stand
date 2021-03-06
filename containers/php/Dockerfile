FROM centos:7

ARG php_display_errors=On
ARG php_opcache_enabled=On

# fix timezone
ENV TIMEZONE Europe/Moscow
RUN ln -snf /usr/share/zoneinfo/$TIMEZONE /etc/localtime && echo $TIMEZONE > /etc/timezone

RUN yum install -y https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm && \
    yum install -y http://rpms.famillecollet.com/enterprise/remi-release-7.rpm && \
    yum-config-manager --enable remi-php72 && \
    yum update -y && \
    yum install -y \
        supervisor \
        git \
        wget \
        php \
        php-fpm \
        php-xml \
        php-intl \
        php-cli \
        php-process \
        php-mbstring \
        php-bcmath \
        php-mcrypt \
        php-pecl-zip \
        php-pecl-memcache \
        php-pecl-memcached \
        php-pecl-amqp \
        php-pecl-apcu \
        php-pecl-apfd \
        php-pecl-http \
        php-mysqlnd \
        php-pdo \
        php-mysql \
        php-pecl-redis \
        php-soap \
        php-pecl-zmq \
        php-pecl-zendopcache && \
    yum clean all

RUN echo "date.timezone=${TIMEZONE}" > /etc/php.d/00-docker-php-date-timezone.ini

# Configure and secure PHP
RUN sed -i "s/soap.wsdl_cache_dir\s*=/;soap.wsdl_cache_dir =/" /etc/php.ini && \
    sed -i -e "s/;daemonize\s*=\s*yes/daemonize = no/g" /etc/php-fpm.conf && \
    sed -i "s/error_log = .*/error_log = \/proc\/self\/fd\/2/" /etc/php-fpm.conf && \
    sed -i "s/memory_limit = 128/memory_limit = -1/" /etc/php.ini && \
    sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/" /etc/php.ini && \
    sed -i "s/short_open_tag = Off/short_open_tag = On/" /etc/php.ini && \
    sed -i "s/display_errors = Off/display_errors = $php_display_errors/" /etc/php.ini && \
    sed -i '/^listen = /clisten = 0.0.0.0:9000' /etc/php-fpm.d/www.conf && \
    sed -i '/^listen.allowed_clients/c;listen.allowed_clients =' /etc/php-fpm.d/www.conf && \
    sed -i '/^;catch_workers_output/ccatch_workers_output = yes' /etc/php-fpm.d/www.conf && \
    sed -i "s/php_admin_flag\[log_errors\] = .*/;php_admin_flag[log_errors] =/" /etc/php-fpm.d/www.conf && \
    sed -i "s/php_admin_value\[error_log\] =.*/;php_admin_value[error_log] =/" /etc/php-fpm.d/www.conf && \
    sed -i "s/php_admin_value\[error_log\] =.*/;php_admin_value[error_log] =/" /etc/php-fpm.d/www.conf && \
    sed -i '/^;clear_env/cclear_env = no' /etc/php-fpm.d/www.conf && \
    sed -i "s/php_value\[soap.wsdl_cache_dir\]\s*=/;php_value[soap.wsdl_cache_dir] =/" /etc/php-fpm.d/www.conf && \
    sed -i "s/opcache.enable=1/opcache.enable=$php_opcache_enabled/" /etc/php.d/10-opcache.ini && \
    echo "access.log = /proc/self/fd/2" >> /etc/php-fpm.d/www.conf

# install composer
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /tmp
ENV COMPOSER_VERSION 1.5.2
# install composer
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /tmp
ENV COMPOSER_VERSION 1.7.3
RUN curl -s -f -L -o /tmp/installer.php https://raw.githubusercontent.com/composer/getcomposer.org/76a7060ccb93902cd7576b67264ad91c8a2700e2/web/installer \
    && php /tmp/installer.php --no-ansi --install-dir=/usr/bin --filename=composer \
    && composer --ansi --version --no-interaction \
    && rm -rf /tmp/* /tmp/.htaccess

RUN mkdir -p /run/php-fpm && touch /run/php-fpm/php-fpm.pid

# install phpunit
RUN wget https://phar.phpunit.de/phpunit-5.7.phar && chmod +x phpunit-5.7.phar && mv phpunit-5.7.phar /usr/local/bin/phpunit

EXPOSE 9000

WORKDIR /

# supervisor configentrypoint
COPY supervisor.services.ini /etc/supervisord.d/supervisor.services.ini
COPY entrypoint.sh /entrypoint.sh

WORKDIR /releases

ENTRYPOINT ["/entrypoint.sh"]
