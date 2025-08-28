{
    "extra_uniticons": ["shadow", "shadow2", "shadowB", "shadowG", "shisyou", "man"]
  , "rooms": {
        "start": {
            "id": 31021000
          , "battle_bg": "room2"
          , "environment": "cave"
          , "start_pos": [0,2]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"reishi_cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% よし！マルティーニ宮殿に侵入したぞ！"
                      , "LINES %avatar% って・・どこだここ・・。"
                      , "SPEAK %avatar% もじょ 宮殿には見えないのだ。"
                      , "LINES %avatar% 宮殿の地下・・ってことかなぁ・・"
                      , "SPEAK %avatar% もじょ そうっぽいのだ。"
                      , "LINES %avatar% 水滴がたれる音が聞こえるね・・。"
                      , "SPEAK %avatar% もじょ それだけじゃないのだ。"
                      , "LINES %avatar% 人の・・うめき声・・？"
                      , "SPEAK %avatar% もじょ ちなみに当たり間だが"
                      , "SPEAK %avatar% もじょ こっからのダンジョンは本当にムズカシイのだ。"
                      , "SPEAK %avatar% もじょ ラストダンジョンだから当たり前なのだ。"
                      , "LINES %avatar% ここまで来たら最後までやるっきゃない！"
                      , "SPEAK %avatar% もじょ なのだ。"
                    ]
                }
              , "enemy0.1": {
                    "trigger": "hero"
                  , "pos":[1,2], "rb":[9,3]
                  , "type": "unit"
                  , "unit": {
                        "pos": [10, 2]
                      , "character_id":-10088
                      , "icon":"shadow"
                      , "add_level":17
                    }
                  , "chain": "enemy0.2"
                }
              , "enemy0.2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [12, 2]
                      , "character_id":-10088
                      , "icon":"shadow"
                      , "add_level":17
                    }
                }
              , "enemy0.3": {
                    "trigger": "hero"
                  , "pos":[11,2], "rb":[13,6]
                  , "type": "unit"
                  , "unit": {
                        "pos": [6, 3]
                      , "character_id":-10088
                      , "icon":"shadow"
                      , "add_level":17
                    }
                  , "chain": "enemy0.4"
                }
              , "enemy0.4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [12, 11]
                      , "character_id":-10088
                      , "icon":"shadow"
                      , "add_level":17
                    }
                }
              , "enemy0.5": {
                    "trigger": "hero"
                  , "pos":[11,8], "rb":[13,13]
                  , "type": "unit"
                  , "unit": {
                        "pos": [11, 7]
                      , "character_id":-10088
                      , "icon":"shadow"
                      , "add_level":17
                    }
                  , "chain": "enemy0.6"
                }
              , "enemy0.6": {
                    "type": "unit"
                  , "unit": {
                        "pos": [3, 13]
                      , "character_id":-10088
                      , "icon":"shadow"
                      , "add_level":17
                    }
                }
              , "old1_speak": {
                    "trigger": "hero"
                  , "pos": [7,12], "rb": [9,12]
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %old1% 囚人 ううう・・助けてくれ・・"
                      , "SPEAK %old1% 囚人 もうここに何十年いるのかも分からない・・"
                      , "SPEAK %old1% 囚人 今は何年だ・・。何月何日なんだ・・。"
                      , "SPEAK %old1% 囚人 お願いだ・・。せめて殺してくれ・・。"
                    ]
                }
              , "man1_speak": {
                    "trigger": "hero"
                  , "pos": [1,12], "rb": [3,12]
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %man1% 囚人 た・・助けてくれ・・"
                      , "SPEAK %man1% 囚人 この地下牢は終身刑か死刑の人間しかいない・・"
                      , "SPEAK %man1% 囚人 死ぬのは怖くない"
                      , "SPEAK %man1% 囚人 しかしこの拷問は耐えられない・・"
                      , "SPEAK %man1% 囚人 ここの拷問官はすさまじすぎる・・。"
                      , "SPEAK %man1% 囚人 お願いだ！殺してくれ！"
                    ]
                }
              , "goto_next": {
                    "trigger": "hero"
                  , "pos": [0,12], "rb":[0,13]
                  , "type": "goto"
                  , "ornament": "goto"
                  , "room": "floor1"
                }
              , "lamp1": {
                    "pos": [3, 0]
                  , "ornament": "lamp"
                }
              , "lamp2": {
                    "pos": [7, 0]
                  , "ornament": "lamp"
                }
              , "lamp3": {
                    "pos": [11, 0]
                  , "ornament": "lamp"
                }
              , "lamp4": {
                    "pos": [5, 10]
                  , "ornament": "lamp"
                }
              , "lamp5": {
                    "pos": [-1, 10]
                  , "ornament": "lamp"
                }
            }
          , "units": [
                {
                    "character_id": -9906
                  , "code": "old1"
                  , "icon":"shisyou"
                  , "pos": [8,7]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "man1"
                  , "icon":"man"
                  , "pos": [1,9]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
      , "floor1": {
            "id": 31021001
          , "battle_bg": "room2"
          , "environment": "cave"
          , "start_pos": [13,2]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"reishi_cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% な・・なんだこりゃ"
                      , "SPEAK %avatar% もじょ 海水なのだ・・。"
                      , "LINES %avatar% なんでこんなんなってんの・・。"
                      , "SPEAK %avatar% もじょ 多分、水牢なのだ。"
                      , "LINES %avatar% 水牢？？"
                      , "SPEAK %avatar% もじょ 拷問のための水浸しの牢屋なのだ。"
                      , "LINES %avatar% なんてひどい・・。"
                    ]
                }
              , "man1_speak": {
                    "trigger": "hero"
                  , "pos":[1,15], "rb":[3,15]
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %man1% 囚人 ううう・・苦しい・・。"
                      , "SPEAK %man1% 囚人 水があるせいで眠れない・・。"
                    ]
                }
              , "man2_speak": {
                    "trigger": "hero"
                  , "pos": [7,15], "rb": [9,15]
                  , "type": "lead"
                  , "leads": [
                        "SPEAK %man2% 囚人 た・・助けてくれ・・"
                      , "SPEAK %man2% 囚人 たのむ・・せめて普通の牢屋に移してくれ・・"
                    ]
                }
              , "enemy1.1": {
                    "trigger": "hero"
                  , "pos":[6,2], "rb":[12,3]
                  , "type": "unit"
                  , "unit": {
                        "pos": [1, 2]
                      , "character_id":-10099
                      , "icon":"shadow"
                      , "add_level":18
                    }
                  , "chain": "enemy1.2"
                }
              , "enemy1.2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [1, 3]
                      , "character_id":-10088
                      , "icon":"shadow"
                      , "add_level":17
                    }
                }
              , "enemy1.3": {
                    "trigger": "hero"
                  , "pos":[0,2], "rb":[5,3]
                  , "type": "unit"
                  , "unit": {
                        "pos": [10, 2]
                      , "character_id":-10099
                      , "icon":"shadow"
                      , "add_level":18
                    }
                  , "chain": "enemy1.4"
                }
              , "enemy1.4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [10, 3]
                      , "character_id":-10088
                      , "icon":"shadow"
                      , "add_level":17
                    }
                }
              , "enemy1.5": {
                    "trigger": "hero"
                  , "pos":[11,8], "rb":[13,12]
                  , "type": "unit"
                  , "unit": {
                        "pos": [11, 17]
                      , "character_id":-10099
                      , "icon":"shadow"
                      , "add_level":18
                    }
                  , "chain": "enemy1.6"
                }
              , "enemy1.6": {
                    "type": "unit"
                  , "unit": {
                        "pos": [12, 17]
                      , "character_id":-10088
                      , "icon":"shadow"
                      , "add_level":17
                    }
                }
              , "enemy1.7": {
                    "trigger": "hero"
                  , "pos":[7,15], "rb":[13,17]
                  , "type": "unit"
                  , "unit": {
                        "pos": [7, 16]
                      , "character_id":-10099
                      , "icon":"shadow"
                      , "add_level":18
                    }
                  , "chain": "enemy1.6"
                }
              , "enemy1.8": {
                    "type": "unit"
                  , "unit": {
                        "pos": [12, 10]
                      , "character_id":-10088
                      , "icon":"shadow"
                      , "add_level":17
                    }
                }
              , "treasure1": {
                    "trigger": "player"
                  , "pos":[13,17]
                  , "type": "treasure"
                  , "ornament": "twinkle"
                  , "item_id": 1003
                }
              , "goto_next": {
                    "trigger": "hero"
                  , "pos": [0,15], "rb":[0,17]
                  , "type": "goto"
                  , "ornament": "goto"
                  , "room": "floor2"
                }
              , "lamp1": {
                    "pos": [3, 0]
                  , "ornament": "lamp"
                }
              , "lamp2": {
                    "pos": [7, 0]
                  , "ornament": "lamp"
                }
              , "lamp3": {
                    "pos": [11, 0]
                  , "ornament": "lamp"
                }
              , "lamp4": {
                    "pos": [5, 13]
                  , "ornament": "lamp"
                }
              , "lamp5": {
                    "pos": [-1, 0]
                  , "ornament": "lamp"
                }
              , "lamp6": {
                    "pos": [15, 0]
                  , "ornament": "lamp"
                }
              , "lamp7": {
                    "pos": [-1, 13]
                  , "ornament": "lamp"
                }
            }
          , "units": [
                {
                    "character_id": -9906
                  , "code": "man1"
                  , "icon":"man"
                  , "pos": [2,10]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "man2"
                  , "icon":"man"
                  , "pos": [8,11]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
                    "character_id": -10082
                  , "icon":"shadowB"
                  , "pos": [4,4]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                  , "add_level":29
                }
             ,  {
                    "character_id": -10082
                  , "icon":"shadowB"
                  , "pos": [0,6]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                  , "add_level":29
                }
             ,  {
                    "character_id": -10082
                  , "icon":"shadowB"
                  , "pos": [8,4]
                  , "unit_class": "ExBrains"
                  , "act_brain": "excurse"
                  , "excurse_path": "666444"
                  , "excurse_step": 3
                  , "add_level":29
                }
            ]
        }
      , "floor2": {
            "id": 31021002
          , "battle_bg": "room2"
          , "environment": "cave"
          , "start_pos": [9,20]
          , "gimmicks": {
                "enemy2.1": {
                    "trigger": "hero"
                  , "pos":[2,19], "rb":[15,20]
                  , "type": "unit"
                  , "unit": {
                        "pos": [0, 13]
                      , "character_id":-10101
                      , "icon":"shadow"
                      , "add_level":6
                    }
                  , "chain": "enemy2.2"
                }
              , "enemy2.2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [1, 14]
                      , "character_id":-10101
                      , "icon":"shadow"
                      , "add_level":6
                    }
                }
              , "enemy2.3": {
                    "trigger": "hero"
                  , "pos":[0,11], "rb":[1,18]
                  , "type": "unit"
                  , "unit": {
                        "pos": [0, 13]
                      , "character_id":-10101
                      , "icon":"shadow"
                      , "add_level":6
                    }
                  , "chain": "enemy2.4"
                }
              , "enemy2.4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [4, 19]
                      , "character_id":-10101
                      , "icon":"shadow"
                      , "add_level":6
                    }
                }
              , "enemy2.5": {
                    "trigger": "hero"
                  , "pos":[0,2], "rb":[1,6]
                  , "type": "unit"
                  , "unit": {
                        "pos": [8, 2]
                      , "character_id":-10101
                      , "icon":"shadow"
                      , "add_level":6
                    }
                  , "chain": "enemy2.6"
                }
              , "enemy2.6": {
                    "type": "unit"
                  , "unit": {
                        "pos": [1, 10]
                      , "character_id":-10101
                      , "icon":"shadow"
                      , "add_level":6
                    }
                }
              , "enemy2.7": {
                    "trigger": "hero"
                  , "pos":[17,0], "rb":[18,14]
                  , "type": "unit"
                  , "unit": {
                        "pos": [17, 14]
                      , "character_id":-10101
                      , "icon":"shadow"
                      , "add_level":6
                    }
                  , "chain": "enemy2.8"
                }
              , "enemy2.8": {
                    "type": "unit"
                  , "unit": {
                        "pos": [18, 14]
                      , "character_id":-10101
                      , "icon":"shadow"
                      , "add_level":6
                    }
                }
              , "man1_speak": {
                    "condition": {"cleared":false}
                  , "trigger": "hero"
                  , "pos":[3,14], "rb":[5,14]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man1%"
                      , "SPEAK %man1% レジスタンス [NAME]か・・？反対側の牢屋にリーダーがいる・・"
                      , "SPEAK %man1% レジスタンス 明日処刑になってしまうが手ひどい傷があって、もはやそれまでもつかどうか・・。"
                      , "SPEAK %man1% レジスタンス 俺ももはやこれまでだ・・。"
                      , "SPEAK %man1% レジスタンス レジスタンスは死すとも自由は死せず・・。レジスタンス万歳・・！"
                    ]
                  , "chain":"man1_dead"
                }
             , "man1_dead": {
                   "type": "unit_exit"
                 , "exit_target": "man1"
               }
              , "gebaru_speak": {
                    "condition": {"cleared":false}
                  , "trigger": "hero"
                  , "pos": [13,14], "rb": [15,14]
                  , "type": "lead"
                  , "leads": [
                         "AALGN %avatar% %gebaru%"
                       , "LINES %gebaru% 誰かいるのか？"
                       , "LINES %avatar% ゲバルさん？ゲバルさんでしょ？生きてたんだね・・"
                       , "LINES %gebaru% [NAME]か？助けに来てくれたんだな・・"
                       , "LINES %avatar% ゲバルさん・・！"
                       , "LINES %gebaru% ・・誰か来る！"
                    ]
                  , "chain":"boss_appear"
                }
              , "boss_appear": {
                    "type": "unit"
                  , "unit": {
                        "pos": [18, 14]
                      , "character_id":-10116
                      , "icon":"shadow2"
                      , "code":"boss"
        					    , "trigger_gimmick": "gebaru_dead"
                      , "bgm": "bgm_bigboss"
                    }
                  , "chain":"boss_speak"
                }
              , "boss_speak": {
                    "type": "lead"
                  , "leads": [
                         "AALGN %avatar% %boss%"
                       , "LINES %boss% ヒョヒョヒョ・・"
                       , "LINES %avatar% 誰だ！"
                       , "LINES %boss% ワシは・・拷問官じゃ。"
                       , "LINES %avatar% 拷問官・・！？"
                       , "LINES %boss% そうじゃ。王直属の反逆者を拷問する役割を仰せつかっている者じゃ・・。"
                       , "LINES %boss% 王からは決して殺さず、最も苦しむように囚人を拷問せよと命令されておる・・。"
                       , "LINES %boss% 地下の水牢は見たかい？"
                       , "LINES %boss% あれはワシが王にいかに水牢が拷問に適してるかを熱心にプレゼンしてのう・・。"
                       , "LINES %boss% ようやく作った傑作なのじゃ！"
                       , "LINES %avatar% サ・・サディスト・・。"
                       , "LINES %boss% そうとも言うかの。ワシの唯一の楽しみは苦痛の悲鳴じゃ。"
                       , "LINES %boss% しかしそやつはつまらん・・。"
                       , "LINES %boss% どんな拷問をしても悲鳴一つ上げんのじゃ！"
                       , "LINES %gebaru% ヘッ！あいにくおれはドMでな！"
                       , "LINES %boss% ククク・・強がりを・・。"
                       , "LINES %boss% 今日はお前にとっておきの拷問を用意したんじゃ・・！"
                       , "LINES %boss% 明日の処刑の前に！お前の！叫び声を！聞かせてくれ！"
                       , "LINES %avatar% ふ・・ふざけるな！！"
                       , "LINES %boss% その前にこのネズミを始末するとしよう・・。"
                    ]
                }
              , "gebaru_dead": {
                    "type": "lead"
                  , "leads": [
                         "AALGN %avatar% %gebaru%"
                       , "LINES %avatar% もう大丈夫だよ！今助け出してあげるからまってて、ゲバルさん！"
                       , "LINES %gebaru% いや、やめるんだ。俺はもう駄目だ"
                       , "LINES %gebaru% オーディンにやられた傷が致命傷になった・・。もう・・助からん・・。"
                       , "LINES %avatar% そ・・そんな・・。"
                       , "LINES %avatar% やだよぅ・・。ゲバルさん！"
                       , "LINES %gebaru% 俺を助けてレジスタンスに戻るんじゃなく王を打倒してくれ。"
                       , "LINES %avatar% ぐすん・・"
                       , "LINES %gebaru% 泣くな・・。前を向け。"
                       , "LINES %gebaru% [NAME]。君にはやることがあるだろう？"
                       , "LINES %gebaru% レジスタンスや人類のためじゃない自分のためにやることが。"
                       , "LINES %avatar% は・・はい・・。"
                       , "LINES %gebaru% ここを抜けたらマルティーニの塔がある。そこに王はいる。"
                       , "LINES %gebaru% 王に行く前に3人のゲートキーパーに気をつけろ・・"
                       , "LINES %gebaru% やつらはとんでもなく強い！！"
                       , "LINES %avatar% ゲバルさん・・。実は・・ボク・・"
                       , "LINES %avatar% わ・・、私は・・。私は・・あなたのことが・・！"
                       , "LINES %gebaru% ・・・"
                       , "LINES %avatar% ・・・なんでもない。"
                       , "LINES %gebaru% ・・レジスタンスはもう解散だ。あとは[NAME]の好きにやれ。"
                       , "LINES %avatar% うん、王を倒すよ！"
                       , "LINES %avatar% 自分のために！・・でも・・レジスタンスの思いも一緒に！"
                       , "LINES %gebaru% おう！・・頼んだ・・ぜ・・。"
                    ]
                  , "chain":"gebaru_exit"
                }
             , "gebaru_exit": {
                   "type": "unit_exit"
                 , "exit_target": "gebaru"
	               , "chain": "gebaru_speak_end"
               }
              , "gebaru_speak_end": {
                    "type": "lead"
                  , "leads": [
                         "LINES %avatar% ゲバルさん・・？ゲバルさん・・！！！"
                       , "SPEAK %avatar% もじょ もう・・死んでるのだ・・。"
                       , "LINES %avatar% ゲバルさん・・。"
                       , "SPEAK %avatar% もじょ 好きだって言わなくてよかったのだ？"
                       , "LINES %avatar% うん・・。いいんだ・・。"
                       , "LINES %avatar% 死ぬ間際まで・・困らせたくなかったから・・。"
                       , "SPEAK %avatar% もじょ ま、ちょっとは大人になったのだ。"
                       , "LINES %avatar% へへ・・。"
                       , "SPEAK %avatar% もじょ ・・じゃ、マルティーニの塔に行くのだ！"
                       , "LINES %avatar% うん！"
                    ]
                  , "chain": "goal"
                }
              , "treasure1": {
                    "trigger": "player"
                  , "pos":[16,19]
                  , "type": "treasure"
                  , "ornament": "twinkle"
                  , "item_id": 1003
                }
              , "treasure2": {
                    "trigger": "player"
                  , "pos":[6,14]
                  , "type": "treasure"
                  , "ornament": "twinkle"
                  , "item_id": 1003
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
              , "goal_cleared": {
                    "condition": {"cleared":true}
                  , "trigger": "player"
                  , "pos":[12,14]
                  , "type": "escape"
                  , "ornament": "goto"
                  , "escape_result": "success"
                }
              , "lamp1": {
                    "pos": [3, 0]
                  , "ornament": "lamp"
                }
              , "lamp2": {
                    "pos": [7, 0]
                  , "ornament": "lamp"
                }
              , "lamp3": {
                    "pos": [11, 0]
                  , "ornament": "lamp"
                }
              , "lamp4": {
                    "pos": [15, 0]
                  , "ornament": "lamp"
                }
              , "lamp5": {
                    "pos": [5, 17]
                  , "ornament": "lamp"
                }
              , "lamp6": {
                    "pos": [13, 17]
                  , "ornament": "lamp"
                }
            }
          , "units": [
                {
                    "condition": {"cleared":false}
                  , "character_id": -20102
                  , "code": "gebaru"
                  , "icon":"man"
                  , "pos": [14,9]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "condition": {"cleared":false}
                  , "character_id": -9907
                  , "code": "man1"
                  , "icon":"man"
                  , "pos": [4,9]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }


    }
}
