#!/bin/bash

LOG=/tmp/stiliam_cms_update.log

echo "Update Script"

# bash git update script
cd /var/www/html >> $LOG
# git --git-dir=/var/www/html/.git pull -q origin master >> $LOG

# check folders
mkdir -p /var/www/html/xc_uploads >> $LOG
mkdir -p /var/www/html/m3u_uploads >> $LOG
mkdir -p /opt/stiliam-backups >> $LOG

# confirm folder permissions
chmod 777 /var/www/html/content/imdb_media/ >> $LOG
chmod 777 /var/www/html/m3u_uploads/ >> $LOG
chmod 777 /var/www/html/xc_uploads >> $LOG

# database mods
# mysql -ustiliam -pstiliam1984 -e "ALTER TABLE cms.servers ADD COLUMN IF NOT EXISTS \`type\` VARCHAR(20); "; >> $LOG


# mysql status check
UP=$(pgrep mysql | wc -l);
if [ "$UP" -ne 1 ];
then
    sudo service mysql start >> $LOG
fi

# nginx status check
nginx_status=$(ps aux | grep nginx | grep -v 'grep' | wc -l)
if [ "$nginx_status" -eq "0" ]; then
   echo "Starting NGINX Streaming Server.";
   /usr/local/nginx/sbin/nginx >> $LOG
fi

echo "Update Complete"
echo " "
