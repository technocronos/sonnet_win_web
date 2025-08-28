#!/bin/sh

echo -n "data sync sonnet native: Are you sure? [y]:"
read answer

if [ $answer == "yes" -o $answer == "y" ];
then


mysqldump sonnet_1 drama_master      | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 dtech_master      | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 gacha_content     | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 gacha_master      | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 help_master       | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 quest_master      | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 room_master       | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 square_master     | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 tournament_master | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 unit_master       | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native

mysqldump sonnet_1 condition_master  | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 equippable_master | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 grade_master      | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 item_level_master | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 item_master       | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 level_master      | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 monster_master    | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 mount_master      | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 place_master      | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 shop_content      | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 set_master        | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 raid_dungeon      | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 raid_prize        | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 nft_equip         | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump sonnet_1 text_master       | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native


# mysql5.1à»ç~Ç∂Ç·Ç»Ç¢Ç∆ÅA--replace Ç™å¯Ç©Ç»Ç¢Åc
mysqldump --no-create-info --replace --where='character_id<0' sonnet_1 character_info | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump --no-create-info --replace --where='text_id<0' sonnet_1 text_log            | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native
mysqldump --no-create-info --replace --where='user_item_id<0' sonnet_1 user_item      | mysql --host=111.171.196.41 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 --database=sonnet_native


echo "done"
fi
