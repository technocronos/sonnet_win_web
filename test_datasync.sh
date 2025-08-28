#!/bin/sh

echo -n "test data sync: Are you sure? [y]:"
read answer

if [ $answer == "yes" -o $answer == "y" ];
then


# リネーム
mysql --database=sonnet_m --execute="
    RENAME TABLE shop_content TO shop_content_bk;
    RENAME TABLE gacha_master TO gacha_master_bk;
"

# データコピー
mysqldump sonnet_1 gacha_master      | mysql --database=sonnet_m
mysqldump sonnet_1 gacha_content     | mysql --database=sonnet_m
mysqldump sonnet_1 item_master       | mysql --database=sonnet_m
mysqldump sonnet_1 shop_content      | mysql --database=sonnet_m

# 差分反映
mysql --database=sonnet_m --execute="

    UPDATE gacha_master
           INNER JOIN gacha_master_bk USING (gacha_id)
    SET gacha_master.price = gacha_master_bk.price;

    UPDATE shop_content
           INNER JOIN shop_content_bk USING (shop_id, item_id)
    SET shop_content.price = shop_content_bk.price
    WHERE shop_content.shop_id = 0;
"

# 古いテーブルを削除
mysql --database=sonnet_m --execute="
    DROP TABLE shop_content_bk, gacha_master_bk;
"


echo "done"

fi
