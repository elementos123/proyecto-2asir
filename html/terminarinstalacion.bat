#!/bin/bash

systemctl stop apache2

mv /var/www/html/install/index2.php /var/www/html/index.php
mv /var/www/html/install/*.* /var/www/html/

systemctl start apache2

rm -rf /var/www/html/terminarinstalacion.bat
rm -rf /var/www/html/install