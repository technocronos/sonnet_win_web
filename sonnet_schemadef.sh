cd /srv/www/test.gree.sonnet.t-cronos.co.jp/var

mkdir test
cd test

mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" battle_log > battle_log.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" character_effect > character_effect.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" character_equipment > character_equipment.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" character_tournament > character_tournament.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" drama_master > drama_master.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" dtech_master > dtech_master.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" flag_log > flag_log.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" gacha_content > gacha_content.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" gacha_master > gacha_master.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" help_master > help_master.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" invitation_log > invitation_log.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" mini_session > mini_session.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" payment_log > payment_log.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" quest_master > quest_master.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" ranking_log > ranking_log.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" room_master > room_master.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" square_master > square_master.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" tournament_master > tournament_master.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" unit_master > unit_master.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" user_info > user_info.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" user_member > user_member.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" user_property > user_property.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_1" user_thumbnail > user_thumbnail.sql

mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" approach_log > approach_log.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" cache_info > cache_info.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" character_info > character_info.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" condition_master > condition_master.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" delivery_log > delivery_log.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" equippable_master > equippable_master.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" grade_master > grade_master.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" history_admiration > history_admiration.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" history_log > history_log.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" history_reply > history_reply.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" incentive_log > incentive_log.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" item_level_master > item_level_master.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" item_master > item_master.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" level_master > level_master.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" message_log > message_log.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" monster_master > monster_master.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" mount_master > mount_master.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" oshirase_log > oshirase_log.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" page_statistics > page_statistics.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" place_master > place_master.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" shop_content > shop_content.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" sphere_info > sphere_info.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" text_log > text_log.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" user_item > user_item.sql
mysqldump --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_2" user_monster > user_monster.sql

cd ../



mkdir prod
cd prod

mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" approach_log > approach_log.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" battle_log > battle_log.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" cache_info > cache_info.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" character_effect > character_effect.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" character_equipment > character_equipment.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" character_info > character_info.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" character_tournament > character_tournament.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" condition_master > condition_master.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" delivery_log > delivery_log.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" drama_master > drama_master.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" dtech_master > dtech_master.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" equippable_master > equippable_master.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" flag_log > flag_log.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" gacha_content > gacha_content.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" gacha_master > gacha_master.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" grade_master > grade_master.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" help_master > help_master.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" history_admiration > history_admiration.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" history_log > history_log.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" history_reply > history_reply.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" incentive_log > incentive_log.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" invitation_log > invitation_log.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" item_level_master > item_level_master.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" item_master > item_master.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" level_master > level_master.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" message_log > message_log.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" mini_session > mini_session.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" monster_master > monster_master.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" mount_master > mount_master.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" oshirase_log > oshirase_log.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" page_statistics > page_statistics.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" payment_log > payment_log.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" place_master > place_master.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" quest_master > quest_master.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" ranking_log > ranking_log.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" room_master > room_master.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" shop_content > shop_content.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" sphere_info > sphere_info.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" square_master > square_master.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" text_log > text_log.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" tournament_master > tournament_master.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" unit_master > unit_master.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" user_info > user_info.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" user_item > user_item.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" user_member > user_member.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" user_monster > user_monster.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" user_property > user_property.sql
mysqldump --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --no-data --compact --skip-create-options "sonnet_m" user_thumbnail > user_thumbnail.sql

cd ../
