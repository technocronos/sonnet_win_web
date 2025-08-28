<?php

/**
 * モンスターに、カテゴリごとにレベル昇順で通し番号を付けるアクション
 */
class MonsterNumberingAction extends AdminBaseAction {

    public function execute() {

        if(ENVIRONMENT_TYPE == 'prod')
            throw new MojaviException('本番では実行できません');

        DataAccessObject::$DEBUG = true;


#         $svc = new Monster_MasterService();

        $dao = new DataAccessObject('sonnet_2');

        $loop = array(
            1=> 'PLT', 2 => 'AQA', 3 => 'ZOB', 4 => 'VRM', 5 => 'ANM',
            6 => 'HUM', 7 => 'DHM', 8 => 'MCN', 9 => 'SHA', 10 => 'LGN',
        );

        foreach($loop as $index => $cat) {

            $sql = '
                SELECT character_id
                FROM monster_master
                    INNER JOIN character_info USING (character_id)
                WHERE monster_master.category = ?
                ORDER BY character_info.exp
            ';

            $ids = $dao->getCol($sql, $index);

            $i = 1;
            foreach($ids as $id) {

                $sql = '
                    UPDATE monster_master
                    SET monster_no = ?
                    WHERE character_id = ?
                ';

                $dao->execute($sql, array(sprintf("{$cat}%02d", $i), $id));

                $i++;
            }
        }

#         var_dump($a, $ret);
        exit();


        return View::SUCCESS;
    }
}
