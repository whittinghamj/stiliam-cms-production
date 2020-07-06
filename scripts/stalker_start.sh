#!/bin/bash
docker stop $(docker ps -a -q)
/usr/bin/docker run -d --net=host -d ministra_5.3 bash -c "/etc/init.d/apache2 restart; tail -f /dev/null"
