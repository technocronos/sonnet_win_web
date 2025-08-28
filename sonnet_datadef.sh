cd /srv/www/test.gree.sonnet.t-cronos.co.jp/var

mkdir gree_test
cd gree_test
mysqldump --skip-extended-insert --compact sonnet_1 drama_master      > drama_master.sql
mysqldump --skip-extended-insert --compact sonnet_1 dtech_master      > dtech_master.sql
mysqldump --skip-extended-insert --compact sonnet_1 gacha_content     > gacha_content.sql
mysqldump --skip-extended-insert --compact sonnet_1 gacha_master      > gacha_master.sql
mysqldump --skip-extended-insert --compact sonnet_1 help_master       > help_master.sql
mysqldump --skip-extended-insert --compact sonnet_1 quest_master      > quest_master.sql
mysqldump --skip-extended-insert --compact sonnet_1 room_master       > room_master.sql
mysqldump --skip-extended-insert --compact sonnet_1 square_master     > square_master.sql
mysqldump --skip-extended-insert --compact sonnet_1 tournament_master > tournament_master.sql
mysqldump --skip-extended-insert --compact sonnet_1 unit_master       > unit_master.sql
mysqldump --skip-extended-insert --compact sonnet_2 condition_master  > condition_master.sql
mysqldump --skip-extended-insert --compact sonnet_2 equippable_master > equippable_master.sql
mysqldump --skip-extended-insert --compact sonnet_2 grade_master      > grade_master.sql
mysqldump --skip-extended-insert --compact sonnet_2 item_level_master > item_level_master.sql
mysqldump --skip-extended-insert --compact sonnet_2 item_master       > item_master.sql
mysqldump --skip-extended-insert --compact sonnet_2 level_master      > level_master.sql
mysqldump --skip-extended-insert --compact sonnet_2 monster_master    > monster_master.sql
mysqldump --skip-extended-insert --compact sonnet_2 mount_master      > mount_master.sql
mysqldump --skip-extended-insert --compact sonnet_2 place_master      > place_master.sql
mysqldump --skip-extended-insert --compact sonnet_2 shop_content      > shop_content.sql
cd ../

mkdir gree_prod
cd gree_prod
mysqldump --skip-extended-insert --compact --host=10.100.14.14 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_gree drama_master      > drama_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.14.14 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_gree dtech_master      > dtech_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.14.14 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_gree gacha_content     > gacha_content.sql
mysqldump --skip-extended-insert --compact --host=10.100.14.14 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_gree gacha_master      > gacha_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.14.14 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_gree help_master       > help_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.14.14 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_gree quest_master      > quest_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.14.14 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_gree room_master       > room_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.14.14 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_gree square_master     > square_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.14.14 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_gree tournament_master > tournament_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.14.14 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_gree unit_master       > unit_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.14.14 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_gree condition_master  > condition_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.14.14 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_gree equippable_master > equippable_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.14.14 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_gree grade_master      > grade_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.14.14 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_gree item_level_master > item_level_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.14.14 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_gree item_master       > item_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.14.14 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_gree level_master      > level_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.14.14 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_gree monster_master    > monster_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.14.14 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_gree mount_master      > mount_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.14.14 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_gree place_master      > place_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.14.14 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_gree shop_content      > shop_content.sql
cd ../

mkdir mbga_test
cd mbga_test
mysqldump --skip-extended-insert --compact sonnet_1 drama_master      > drama_master.sql
mysqldump --skip-extended-insert --compact sonnet_1 dtech_master      > dtech_master.sql
mysqldump --skip-extended-insert --compact sonnet_m gacha_content     > gacha_content.sql
mysqldump --skip-extended-insert --compact sonnet_m gacha_master      > gacha_master.sql
mysqldump --skip-extended-insert --compact sonnet_1 help_master       > help_master.sql
mysqldump --skip-extended-insert --compact sonnet_1 quest_master      > quest_master.sql
mysqldump --skip-extended-insert --compact sonnet_1 room_master       > room_master.sql
mysqldump --skip-extended-insert --compact sonnet_1 square_master     > square_master.sql
mysqldump --skip-extended-insert --compact sonnet_1 tournament_master > tournament_master.sql
mysqldump --skip-extended-insert --compact sonnet_1 unit_master       > unit_master.sql
mysqldump --skip-extended-insert --compact sonnet_2 condition_master  > condition_master.sql
mysqldump --skip-extended-insert --compact sonnet_2 equippable_master > equippable_master.sql
mysqldump --skip-extended-insert --compact sonnet_2 grade_master      > grade_master.sql
mysqldump --skip-extended-insert --compact sonnet_2 item_level_master > item_level_master.sql
mysqldump --skip-extended-insert --compact sonnet_m item_master       > item_master.sql
mysqldump --skip-extended-insert --compact sonnet_2 level_master      > level_master.sql
mysqldump --skip-extended-insert --compact sonnet_2 monster_master    > monster_master.sql
mysqldump --skip-extended-insert --compact sonnet_2 mount_master      > mount_master.sql
mysqldump --skip-extended-insert --compact sonnet_2 place_master      > place_master.sql
mysqldump --skip-extended-insert --compact sonnet_m shop_content      > shop_content.sql
cd ../

mkdir mbga_prod
cd mbga_prod
mysqldump --skip-extended-insert --compact --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_m drama_master      > drama_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_m dtech_master      > dtech_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_m gacha_content     > gacha_content.sql
mysqldump --skip-extended-insert --compact --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_m gacha_master      > gacha_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_m help_master       > help_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_m quest_master      > quest_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_m room_master       > room_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_m square_master     > square_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_m tournament_master > tournament_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_m unit_master       > unit_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_m condition_master  > condition_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_m equippable_master > equippable_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_m grade_master      > grade_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_m item_level_master > item_level_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_m item_master       > item_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_m level_master      > level_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_m monster_master    > monster_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_m mount_master      > mount_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_m place_master      > place_master.sql
mysqldump --skip-extended-insert --compact --host=10.100.12.133 --user=sonnet_mas --password=HHil0OjGZTXv0JEh6IN5 sonnet_m shop_content      > shop_content.sql
cd ../
