# Centro di diagnostica - progetto SWBD

# Start CRON JOB

crontab -e

* * * * * docker exec -it centro_diagnostica-php-apache-1 php src/check_codicetemp.php

sudo service cron start
