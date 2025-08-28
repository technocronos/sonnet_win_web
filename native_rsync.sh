#!/bin/sh

echo -n "rsync sonnet native: Are you sure? [y]:"
read answer

if [ $answer == "yes" -o $answer == "y" ];
then


### SWGWEB01
rsync -av --delete --exclude=".svn" --exclude="var" --exclude="webapp/config.php" --exclude="htdocs/swf/*" --exclude="htdocs/assets/*" --exclude="phpMyAdmin" -e ssh  /root/stage/sonnet/ 111.171.196.41:/srv/www/native.sonnet.crns-game.net

fi
