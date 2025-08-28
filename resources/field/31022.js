{
    "extra_uniticons": ["shadow2", "shadowG"]
  , "rooms": {
        "start": {
            "id": 31022000
          , "battle_bg": "room3"
          , "environment": "none"
          , "start_pos": [9,16]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% ここがマルティーニの塔か・・。"
                      , "LINES %avatar% ここにマルティーニ王がいる・・。"
                      , "SPEAK %avatar% もじょ 最後の決戦なのだ。"
                    ]
                }
              , "enemy0.1": {
                    "trigger": "hero"
                  , "pos":[0,14], "rb":[8,16]
                  , "type": "unit"
                  , "unit": {
                        "pos": [1, 9]
                      , "character_id":-10098
                      , "icon":"shadow2"
                      , "add_level":25
                    }
                  , "chain": "enemy0.2"
                }
              , "enemy0.2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [3, 9]
                      , "character_id":-10098
                      , "icon":"shadow2"
                      , "add_level":25
                    }
                }
              , "enemy0.3": {
                    "trigger": "hero"
                  , "pos":[10,14], "rb":[18,16]
                  , "type": "unit"
                  , "unit": {
                        "pos": [15, 9]
                      , "character_id":-10098
                      , "icon":"shadow2"
                      , "add_level":25
                    }
                  , "chain": "enemy0.4"
                }
              , "enemy0.4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [17, 9]
                      , "character_id":-10098
                      , "icon":"shadow2"
                      , "add_level":25
                    }
                }
              , "enemy0.5": {
                    "trigger": "hero"
                  , "pos":[4,9], "rb":[14,11]
                  , "type": "unit"
                  , "unit": {
                        "pos": [8, 6]
                      , "character_id":-10098
                      , "icon":"shadow2"
                      , "add_level":25
                    }
                  , "chain": "enemy0.6"
                }
              , "enemy0.6": {
                    "type": "unit"
                  , "unit": {
                        "pos": [10, 6]
                      , "character_id":-10098
                      , "icon":"shadow2"
                      , "add_level":25
                    }
                }
              , "boss_speak": {
                    "trigger": "hero"
                  , "pos": [7,2], "rb": [11,6]
                  , "ignition": {"unit_exist":"boss"}
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %boss%"
                      , "LINES %boss% ほう、侵入者とは久々だな"
                      , "LINES %boss% ちょうど退屈していたところだ。遊び相手にはちょうどよかろう・・"
                      , "LINES %boss% 我はサイクロプス・・ゲートキーパーの一人だ"
                      , "LINES %boss% 粉々にして喰らってくれるわ！！"
                    ]
                }
              , "goto_next": {
                    "trigger": "hero"
                  , "pos": [9,2]
                  , "ignition": {"unit_nonexist":"boss"}
                  , "type": "goto"
                  , "room": "floor1"
                }
              , "torch1": {
                    "pos": [7, 4]
                  , "ornament": "torch"
                }
              , "torch2": {
                    "pos": [7, 5]
                  , "ornament": "torch"
                }
              , "torch3": {
                    "pos": [11, 4]
                  , "ornament": "torch"
                }
              , "torch4": {
                    "pos": [11, 5]
                  , "ornament": "torch"
                }
            }
          , "units": [
                {
                    "character_id": -10100
                  , "pos": [9, 3]
                  , "icon":"shadowG"
                  , "code":"boss"
                  , "add_level":28
                  , "act_brain": "rest"
                  , "bgm": "bgm_bossbattle"
                }
            ]
        }
      , "floor1": {
            "id": 31022001
          , "battle_bg": "room3"
          , "environment": "none"
          , "start_pos": [9,16]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "SPEAK %avatar% もじょ ここが二階なのだ。"
                    ]
                }
              , "enemy1.1": {
                    "trigger": "hero"
                  , "pos":[5,7], "rb":[15,12]
                  , "type": "unit"
                  , "unit": {
                        "pos": [5, 9]
                      , "character_id":-10117
                      , "icon":"shadow2"
                    }
                  , "chain": "enemy1.2"
                }
              , "enemy1.2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [13, 9]
                      , "character_id":-10117
                      , "icon":"shadow2"
                    }
                }
              , "enemy1.3": {
                    "trigger": "hero"
                  , "pos":[0,7], "rb":[4,12]
                  , "type": "unit"
                  , "unit": {
                        "pos": [1, 3]
                      , "character_id":-10117
                      , "icon":"shadow2"
                    }
                  , "chain": "enemy1.4"
                }
              , "enemy1.4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [3, 3]
                      , "character_id":-10117
                      , "icon":"shadow2"
                    }
                }
              , "enemy1.5": {
                    "trigger": "hero"
                  , "pos":[14,7], "rb":[18,12]
                  , "type": "unit"
                  , "unit": {
                        "pos": [15, 4]
                      , "character_id":-10117
                      , "icon":"shadow2"
                    }
                  , "chain": "enemy1.6"
                }
              , "enemy1.6": {
                    "type": "unit"
                  , "unit": {
                        "pos": [17, 4]
                      , "character_id":-10117
                      , "icon":"shadow2"
                    }
                }
              , "treasure1": {
                    "trigger": "player"
                  , "pos":[0,12]
                  , "type": "treasure"
                  , "ornament": "twinkle"
                  , "item_id": 1003
                }
              , "treasure2": {
                    "trigger": "player"
                  , "pos":[18,12]
                  , "type": "treasure"
                  , "ornament": "twinkle"
                  , "item_id": 1003
                }
              , "torch1": {
                    "pos": [7, 3]
                  , "ornament": "torch"
                }
              , "torch2": {
                    "pos": [7, 7]
                  , "ornament": "torch"
                }
              , "torch3": {
                    "pos": [7, 11]
                  , "ornament": "torch"
                }
              , "torch4": {
                    "pos": [11, 3]
                  , "ornament": "torch"
                }
              , "torch5": {
                    "pos": [11, 7]
                  , "ornament": "torch"
                }
              , "torch6": {
                    "pos": [11, 11]
                  , "ornament": "torch"
                }
              , "boss_speak": {
                    "trigger": "hero"
                  , "pos": [8,2], "rb": [10,4]
                  , "ignition": {"unit_exist":"boss"}
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %boss%"
                      , "LINES %boss% ほう、侵入者とは久々だな"
                      , "LINES %boss% ちょうど退屈していたところだ。遊び相手にはちょうどよかろう・・"
                      , "LINES %boss% 我は暗闇の騎士・・ゲートキーパーの一人だ。"
                      , "LINES %boss% わが暗黒剣の錆にしてくれる！！"
                    ]
                }
              , "goto_next": {
                    "trigger": "hero"
                  , "pos": [9,2]
                  , "type": "goto"
                  , "ignition": {"unit_nonexist":"boss"}
                  , "room": "floor2"
                }
            }
          , "units": [
                {
                    "character_id": -10092
                  , "pos": [9,3]
                  , "icon":"shadowG"
                  , "code":"boss"
                  , "add_level":26
                  , "act_brain": "rest"
                  , "bgm": "bgm_bossbattle"
                }
            ]
        }
      , "floor2": {
            "id": 31022002
          , "battle_bg": "room3"
          , "environment": "none"
          , "start_pos": [6,12]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "SPEAK %avatar% もじょ ここが三階なのだ。"
                    ]
                }
              , "enemy2.1": {
                    "trigger": "hero"
                  , "pos":[0,8], "rb":[4,12]
                  , "type": "unit"
                  , "unit": {
                        "pos": [2, 4]
                      , "character_id":-10117
                      , "icon":"shadow2"
                    }
                }
              , "enemy2.2": {
                    "trigger": "hero"
                  , "pos":[0,2], "rb":[8,3]
                  , "type": "unit"
                  , "unit": {
                        "pos": [12, 3]
                      , "character_id":-10117
                      , "icon":"shadow2"
                    }
                }
              , "enemy2.3": {
                    "trigger": "hero"
                  , "pos":[9,2], "rb":[12,8]
                  , "type": "unit"
                  , "unit": {
                        "pos": [12, 8]
                      , "character_id":-10117
                      , "icon":"shadow2"
                    }
                }
              , "treasure1": {
                    "trigger": "player"
                  , "pos":[12,2]
                  , "type": "treasure"
                  , "ornament": "twinkle"
                  , "item_id": 1003
                }
              , "torch1": {
                    "pos": [0, 8]
                  , "ornament": "torch"
                }
              , "torch2": {
                    "pos": [0, 13]
                  , "ornament": "torch"
                }
              , "torch3": {
                    "pos": [12, 8]
                  , "ornament": "torch"
                }
              , "torch4": {
                    "pos": [12, 13]
                  , "ornament": "torch"
                }
              , "boss_speak": {
                    "trigger": "hero"
                  , "pos": [9,8]
                  , "ignition": {"unit_exist":"boss"}
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %boss%"
                      , "LINES %boss% ほう、侵入者とは久々だな"
                      , "LINES %boss% ここまでたどり着くとはたいしたものだ"
                      , "LINES %boss% 我はドラゴンゾンビ・・最後のゲートキーパーだ。"
                      , "LINES %boss% 王はこの上にいらっしゃる。"
                      , "LINES %boss% ここを通すわけにはいかん！"
                    ]
                }
              , "goto_next": {
                    "trigger": "hero"
                  , "pos": [6,6]
                  , "type": "goto"
                  , "ignition": {"unit_nonexist":"boss"}
                  , "room": "floor3"
                }
             }
          , "units": [
                {
                    "character_id": -10118
                  , "pos": [8,8]
                  , "icon":"shadowG"
                  , "code":"boss"
                  , "act_brain": "rest"
                  , "bgm": "bgm_bossbattle"
                }
            ]
        }
      , "floor3": {
            "id": 31022003
          , "battle_bg": "room3"
          , "environment": "cave"
          , "start_pos": [6,7]
          , "gimmicks": {
                "open_comment": {
                    "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %king% よく来たな。勇者よ。"
                      , "LINES %avatar% ・・あなたが・・マルティーニ王。"
                      , "SPEAK %avatar% もじょ すごい威圧感なのだ・・"
                      , "LINES %king% そうじゃ。久しぶりじゃな・・？"
                      , "LINES %avatar% 夢で一度お会いして以来だね・・。"
                      , "LINES %king% 我はお前のことをずっと待っていた・・おそらく・・300年ずっと・・"
                      , "LINES %king% 今から300年前の話じゃ・・"
                      , "LINES %king% 我は劣勢だった人間との100年戦争に終止符を打つべくマルス・マルティーニと一騎打ちをした・・"
                      , "LINES %king% そして我は奴に勝利した・・。"
                      , "LINES %king% しかし、死の間際、マルス・マルティーニが驚くべき提案を持ちかけたのだ・・"
                      , "LINES %king% 『どの道文明から背を向けた亜人間は絶滅する』"
                      , "LINES %king% 『ならば私になりすまし人間としていきるがよい』・・とな。"
                      , "LINES %avatar% ・・・"
                      , "LINES %king% やつはそのまま死んでいた・・"
                      , "LINES %avatar% ・・・そうだったのか・・。"
                      , "NOTIF ・・王の話は続いた。"
                      , "LINES %king% 我は人間と戦いながら人間の文明の光にあこがれていたのかもしれん。"
                      , "LINES %king% 我はその日より、マルス・マルティーニと入れ替わり、人間の軍を率いてトロル族を滅ぼした・・"
                      , "LINES %king% 我は古臭い因習としきたりから放たれて自由になった・・。"
                      , "LINES %king% ・・ところが困ったことがあった。"
                      , "NOTIF 王は苦悩の表情を浮かべた・・"
                      , "LINES %king% トロルは人間との交配がうまくいかんのだ・・我もいつかは死ぬ。"
                      , "LINES %king% 300年、ただ孤独なだけであった。"
                      , "LINES %king% ・・だがお前があらわれた・・クゥクゥクゥ・・"
                      , "NOTIF ・・王の表情が一転した！"
                      , "LINES %king% トロルの末裔であるお前なら我の悲願がかなうにちがいないでな！！"
                      , "LINES %avatar% ・・なんでそれを！！"
                      , "LINES %king% ラミア族のスパイから聞いておったぞ！お前の事は！"
                      , "LINES %king% 本当はレジスタンスなどどうでもよかったのだ。"
                      , "LINES %king% リーダーを生かしておいたのはお前をおびきよせるため！"
                      , "LINES %king% さあ！覚悟するがいい！！"
                    ]
                }
              , "king_last": {
                    "type": "lead"
                  , "leads": [
                        "LINES %king% ぐはっ！！"
                      , "LINES %king% まさか我が倒されるとは・・。"
                      , "LINES %king% フフフ・・我も長く生きすぎたか・・。"
                      , "LINES %king% お前の手でこうなったのは運命かも知れん・・。"
                      , "LINES %king% 最後に一つだけ聞こう。"
                      , "LINES %king% 我になりすましマルティーニ王として生きる気はないか？"
                      , "LINES %king% 世界の王となることができるのだぞ？"
                      , "LINES %avatar% やーだよ！王なんてできるわけないじゃない！"
                      , "LINES %avatar% ボクはシショーやもじょやみんなと生きるんだ"
                      , "LINES %king% フフフ・・そうか・・。あいわかった。"
                      , "LINES %king% しかしお前はブラックトロルの血が入っておる。"
                      , "LINES %king% 呪われた血がな・・。"
                      , "LINES %king% いつまでそんなことを言ってられるか地獄でみておるぞ・・！"
                      , "LINES %king% さらばじゃ・・。"
                    ]
                }
              , "last_speak": {
                    "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 0"
                      , "LINES %avatar% 終わったね・・なにもかも・・"
                      , "SPEAK %avatar% もじょ 長い長い旅だったのだ・・"
                      , "LINES %avatar% これから世界はどうなるのかな・・"
                      , "SPEAK %avatar% もじょ なるようになるのだ。"
                      , "LINES %avatar% 言い方冷たいなぁ・・。"
                      , "SPEAK %avatar% もじょ 謎につつまれてたマルティーニ王の真実が明らかになったのだ"
                      , "SPEAK %avatar% もじょ そりゃ世界もガラリと変わるのだ"
                      , "SPEAK %avatar% もじょ マルティーニの圧政もなくなるのだ"
                      , "LINES %avatar% ま、これ以上ボクたちにできることも無いしね"
                      , "SPEAK %avatar% もじょ 島に帰るのだ。"
                      , "LINES %avatar% シショーも待ってるしね。"
                      , "SPEAK %avatar% もじょ そういやジジィのこと忘れてたのだ"
                      , "LINES %avatar% ・・じゃ、行きますか"
                      , "SPEAK %avatar% もじょ 行くのだ！"
                      , "SPEAK %avatar% もじょ ああ、それと・・"
                      , "LINES %avatar% え？まだなんかあるの？"
                      , "SPEAK %avatar% もじょ 最後までつきあってくれたこのゲームをやってるプレイヤーにはお礼を言わねばならないのだ。"
                      , "LINES %avatar% そりゃそうだね！"
                      , "SPEAK %avatar% もじょ 最後までつきあってくれて心からお礼を言うのだ。"
                      , "LINES %avatar% また会おうね！本当にありがとう！"
                      , "LINES %avatar% バイバイ！"
                      , "NOTIF こうしてマルティーニ王は死んだ・・"
                      , "NOTIF マルティーニは王政から紆余曲折を経て民主制に移行し"
                      , "NOTIF 世界はまた変わっていった・・"
                      , "NOTIF マルティーニ王を打倒したという少女は"
                      , "NOTIF その後、ようとして行方は知れなかったという・・"
                      , "NOTIF グラフィックデザイン\n関口綾乃"
                      , "NOTIF JewelSaviorFREE(http://www.jewel-s.jp/)"
                      , "NOTIF プログラミング\n山内陽一郎\n久保河内祐樹"
                      , "NOTIF 企画\n山内陽一郎\n久保河内祐樹"
                      , "NOTIF シナリオ\n山内陽一郎"
                      , "NOTIF サウンド\n山内陽一郎"
                      , "NOTIF ディレクター・統括\n山内陽一郎"
                      , "NOTIF プロデューサー\n山内陽一郎"
                      , "NOTIF Presented By\n株式会社テクノクロノス"
                    ]
                  , "chain":"goal"
                }
              , "torch1": {
                    "pos": [4, 5]
                  , "ornament": "torch"
                }
              , "torch2": {
                    "pos": [8, 5]
                  , "ornament": "torch"
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
            }
          , "units": [
                {
                    "character_id": -10119
                  , "code": "king"
                  , "icon":"shadowG"
                  , "pos": [6,2]
                  , "act_brain": "rest"
                  , "early_gimmick": "king_last"
                  , "trigger_gimmick": "last_speak"
                  , "bgm": "bgm_bigboss"
                }
            ]
        }
    }
}
