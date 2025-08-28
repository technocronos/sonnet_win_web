#!/bin/bash

cd /root/sonnet_backups
rm -fR `date -d '-7day' '+%Y-%m-%d'`
mkdir `date '+%Y-%m-%d'`
cd `date '+%Y-%m-%d'`

mysqldump sonnet character_effect | gzip > character_effect.sql.gz
mysqldump sonnet character_equipment | gzip > character_equipment.sql.gz
mysqldump sonnet character_info | gzip > character_info.sql.gz
mysqldump sonnet character_tournament | gzip > character_tournament.sql.gz
mysqldump sonnet flag_log | gzip > flag_log.sql.gz
mysqldump sonnet invitation_log | gzip > invitation_log.sql.gz
mysqldump sonnet payment_log | gzip > payment_log.sql.gz
mysqldump sonnet ranking_log | gzip > ranking_log.sql.gz
mysqldump sonnet user_info | gzip > user_info.sql.gz
mysqldump sonnet user_item | gzip > user_item.sql.gz
mysqldump sonnet user_member | gzip > user_member.sql.gz
