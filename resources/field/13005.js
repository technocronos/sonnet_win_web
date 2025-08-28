{
    "extra_uniticons": ["boy", "man", "woman", "shisyou"]
  , "rooms": {
        "start": {
            "id": 13005000
          , "battle_bg": "forest"
          , "environment": "grass"
          , "bgm": "bgm_home"
          , "start_pos_on": {
                "start": [10,11]
            }
          , "gimmicks": {
                "open_comment": {
                    "condition": {"cleared":false}
        				  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %avatar% もじょ 牧場についたのだ"
                      , "SPEAK %man1% 農夫 おーい。[NAME]も来てたのかー"
                      , "LINES %avatar% こんにちわー。シショー来てますかー？"
                      , "SPEAK %man1% 農夫 柵の向こうのほうにいるんじゃねーかなぁ"
                      , "LINES %avatar% じゃ、行ってみるか"
                      , "UMOVE %avatar% 662222"
                      , "LINES %avatar% あ！いた！シショー"
                      , "UMOVE %avatar% 6"
                      , "LINES %shisyou% おお[NAME]じゃないか"
                      , "LINES %shisyou% どうじゃ？赤草の実はあったじゃろ？"
                      , "LINES %avatar% え？えと…見つかんなくてー…"
                      , "LINES %avatar% ウロウロしてたらこんなとこ出ちゃったの"
                      , "LINES %shisyou% ずいぶんウロウロしたもんじゃの"
                      , "LINES %shisyou% 実がなるところはいつも行っとるじゃろが"
                      , "LINES %avatar% いや、えと…"
                      , "LINES %avatar% そこにはなかったの！ひとつもなかったの！"
                      , "LINES %shisyou% 一つもなかった？ちったぁあったじゃろが"
                      , "SPEAK %avatar% もじょ ちったぁあったやつはみんなこいつが食ったのだ"
                      , "LINES %avatar% あ！もじょっ！！"
                      , "LINES %shisyou% なんっじゃと！？このバカものっ！！"
                      , "LINES %avatar% ひえっ！！"
                      , "LINES %shisyou% 罰として牧草の刈り取りを手伝うのじゃ"
                      , "LINES %shisyou% わしは先に家に帰っておるからの。真面目にやるんじゃぞ"
                      , "LINES %avatar% はーい・・"
                   ]
                  , "chain" : "galuf_exit"
                }
             , "galuf_exit": {
                   "type": "unit_exit"
                 , "exit_target": "shisyou"
	               , "chain": "speak2"
               }
             , "speak2": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% あーあ・・怒らせちゃった・・"
                      , "LINES %avatar% も～じょ～なんで言っちゃうの！？"
                      , "SPEAK %avatar% もじょ お前がもじょの分まで全部食っちゃったからなのだ！"
                      , "LINES %avatar% 根に持ってたんだ…"
                      , "LINES %avatar% ハァ～めんどくさ・・やるしかないか・・"
                    ]
	               , "chain": "man4_appear"
               }
             , "man4_appear": {
                    "type": "unit"
                  , "unit": {
                        "character_id": -9906
                      , "code": "man4"
                      , "pos": [12, 10]
                      , "icon":"man"
                      , "union": 1
                      , "act_brain": "rest"
                      , "brain_noattack": true
                    }
                  , "chain": "speak3"
               }
              , "speak3": {
                    "type": "lead"
                  , "leads": [
                        "UMOVE %man4% 222"
                      , "UALGN %man4% 2"
                      , "UALGN %avatar% 1"
                      , "SPEAK %man4% 農夫 ハァハァ…じいさん、帰っちゃったか？"
                      , "LINES %avatar% え？帰っちゃいましたよ。何かあったんですか？"
                      , "SPEAK %man4% 農夫 南の柵の牛がほとんど骨になってるんだ・・。"
                      , "LINES %avatar% え！？なにそれ！？"
                      , "SPEAK %man4% 農夫 たぶん狼に食われたんだ・・"
                      , "SPEAK %man4% 農夫 少し前から川向こうの森から雄叫びが聞こえてたからな…"
                      , "SPEAK %man4% 農夫 それで、息子が捕まえてやるって一人で行ったんだが"
                      , "SPEAK %man4% 農夫 食われ方を見てるとただの狼じゃない気がしてきてな…"
                      , "LINES %avatar% ボク探してきます。ついでに狼も退治しちゃう！"
                      , "SPEAK %man4% 農夫 いや…やめなさい！あぶな・・"
                      , "LINES %avatar% 行ってきまーす！"
                      , "UMOVE %avatar% 4888844"
                      , "SPEAK %man4% 農夫 って、おーい！"
                      , "SPEAK %man4% 農夫 ………若いのは血気盛んだな"
                    ]
                  , "chain": "goal"
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
            }
          , "units": [
                {
                    "character_id": -9902
                  , "code": "shisyou"
                  , "icon":"shisyou"
                  , "pos": [14,7]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                  "character_id": -9906
                  , "code": "man1"
                  , "pos": [9,9]
                  , "icon":"man"
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                  "character_id": -9906
                  , "code": "man2"
                  , "pos": [5,5]
                  , "icon":"man"
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                  "character_id": -9906
                  , "code": "man3"
                  , "pos": [3,7]
                  , "icon":"man"
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
    }
}
