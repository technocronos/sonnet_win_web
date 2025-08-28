#!/bin/sh

echo -n "rsync sonnet stage: Are you sure? [y]:"
read answer

if [ $answer == "yes" -o $answer == "y" ];
then


### stage
svn update /root/stage/sonnet/

fi
