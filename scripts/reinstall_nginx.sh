#!/bin/bash
sudo killall nginx
service php7.2-fpm stop
sudo rm -rf /usr/local/nginx*

cd /opt/nginx/
wget http://stiliam.com/downloads/nginx-1.15.8.tar.gz
tar -zxvf nginx-1.15.8.tar.gz
git clone http://git.genexnetworks.net/whittinghamj/nginx-rtmp-module.git

cd nginx-1.15.8
./configure --with-http_ssl_module --add-module=../nginx-rtmp-module

make -j4

sudo make install
mv /usr/local/nginx/conf/nginx.conf /usr/local/nginx/conf/nginx.conf.bak
wget -O /usr/local/nginx/conf/nginx.conf http://stiliam.com/downloads/nginx_server.txt

mkdir -p /var/log/nginx
touch /var/log/nginx/error.log
touch /var/log/nginx/access.log

wget -q -O /usr/local/nginx/conf/server.key http://stiliam.com/downloads/server.key.txt
wget -q -O /usr/local/nginx/conf/server.crt http://stiliam.com/downloads/server.crt.txt
wget -q -O /usr/local/nginx/conf/server.csr http://stiliam.com/downloads/server.csr.txt

/usr/local/nginx/sbin/nginx
service php7.2-fpm restart
cd /root