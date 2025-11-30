FROM php:8.4.14-fpm

# installing dependencies
RUN apt-get update -y && apt-get install -y openssl zip unzip git cron nano

# installing composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# setting up the working dir
WORKDIR /app

# copying the full project (this is not ideally for prod env but for dev env works)
COPY . /app

# installing composer dependencies
RUN composer install

# Copying cron script and adding execution permissions
COPY ./.docker/cron/cron_script.sh /usr/local/bin/cron_script.sh
RUN chmod +x /usr/local/bin/cron_script.sh

# Setting up the crontab file
COPY ./.docker/cron/crontab /etc/cron.d/laravel-scheduler
RUN chmod 0644 /etc/cron.d/laravel-scheduler

# creating the cron.log file
RUN touch /var/log/cron.log

# Exposing Laravel port
EXPOSE 8000

# Copying entrypoint script and adding permissions to run
COPY ./.docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

