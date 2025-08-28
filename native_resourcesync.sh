#!/bin/sh

echo -n "resourcesync sonnet native: Are you sure? [y]:"
read answer

if [ $answer == "yes" -o $answer == "y" ];
then


### SWGWEB01
rsync -av --delete -e ssh  /root/stage/sonnet/htdocs/assets/ 111.171.196.41:/srv/www/default/htdocs/assets


fi
