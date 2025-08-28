{
    "extra_uniticons": ["shadow","shadow2","shadowB", "shadowG", "man"]
  , "extra_maptips": [2548]
   ,"rooms": {
        "start": {
            "id": 98011000
          , "battle_bg": "forest"
          , "environment": "grass"
          , "start_pos": [7, 17]
          , "gimmicks": {
                "start": {
                    "trigger":"rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% うわー、ここがブラックトロルクエストの世界の中？"
                      , "SPEAK %avatar% もじょ そうみたいなのだ"
                    ]
                  , "chain": "speak_omake"
                }
              , "speak_omake": {
                    "type": "lead"
                  , "one_shot": 980110000
                  , "leads": [
                        "NOTIF ジャジャーン♪"
                      , "NOTIF ようこそ、300年前の世界へ"
                      , "NOTIF 君はこれからマルティーニの雑兵だ"
                      , "NOTIF 出世して、英雄マルスマルティーニとともにブラックトロルの王を倒すのだ"
                      , "LINES %avatar% あら、マルティーニ1世になれるわけじゃないのね・・"
                      , "SPEAK %avatar% もじょ どうせならマルティーニ1世になるようにすればいいのになのだ"
                      , "SPEAK %avatar% もじょ ★1なのだ"
                      , "LINES %avatar% まあまあ・・で、どこでコマンド打てばいいんだろう？"
                      , "SPEAK %avatar% もじょ この世界の中央にコントロールセンターの塔があるらしいからそこに行くのだ。"
                      , "LINES %avatar% 了解・・っていってもなかなか広いなぁ・・"
                      , "SPEAK %avatar% もじょ あ、あと何度か言ってるけどイベントは上級者向けでムズカシイのだ"
                      , "SPEAK %avatar% もじょ ストーリーやモンスターの洞窟でレベルを上げて挑むのだ"
                      , "SPEAK %avatar% もじょ 時間かけてじっくり攻略するといいのだ"
                    ]
                }
              , "enemy1": {
                    "trigger": "hero"
                  , "pos": [0,13], "rb": [13,15]
                  , "type": "unit"
                  , "unit": {
                        "pos": [7, 10]
                      , "character_id":-9110
                      , "icon":"shadow"
                      , "code": "ork1"
                    }
                  , "chain": "enemy1_speak"
                }
              , "enemy1_speak": {
                    "type": "lead"
                  , "leads": [
                        "LINES %ork1% オーク・・！"
                    ]
                  , "chain": "enemy2"
                }
              , "enemy2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [8, 10]
                      , "character_id":-9110
                      , "icon":"shadow"
                      , "code": "ork2"
                    }
                  , "chain": "enemy2_speak"
                }
              , "enemy2_speak": {
                    "type": "lead"
                  , "leads": [
                        "LINES %ork2% オーク・・！"
                    ]
                  , "chain": "speak1"
                }
              , "man1": {
                    "trigger": "hero"
                  , "pos": [6,0], "rb": [11,8]
                  , "type": "unit"
                  , "unit": {
                        "pos": [6, 3]
                      , "character_id":-9908
                      , "icon":"man"
                      , "code": "man1"
                      , "union": 1
                      , "act_brain": "rest"
                      , "brain_noattack": true
                    }
                  , "chain": "man2"
                }
              , "man2": {
                    "type": "unit"
                  , "unit": {
                        "pos": [8, 3]
                      , "character_id":-9908
                      , "icon":"man"
                      , "code": "man2"
                      , "union": 1
                      , "act_brain": "rest"
                      , "brain_noattack": true
                      , "unit_class": "98011Man2"
                    }
                  , "chain": "kosan"
                }
              , "kosan": {
                    "type": "unit"
                  , "unit": {
                        "pos": [7, 4]
                      , "character_id":-9908
                      , "icon":"man"
                      , "code": "kosan"
                      , "union": 1
                      , "unit_class": "98011Kosan"
                    }
                  , "chain": "gamer_speak"
                }
              , "gamer_speak": {
                    "type": "lead"
                  , "leads": [
                        "LINES %man1% うわああ！た、助けてくれ！"
                      , "LINES %man2% なんでこんなに強いんだ！もうダメだ！"
                      , "LINES %kosan% 追いかけてきたぞ！"
                    ]
                  , "chain": "enemy3"
                }
              , "enemy3": {
                    "type": "unit"
                  , "unit": {
                        "pos": [7, 0]
                      , "character_id":-9112
                      , "icon":"shadow2"
                      , "code": "orkking"
                      , "act_brain": "rest"
                      , "unit_class": "98011Orkking"
                      , "bgm": "bgm_bossbattle"
                    }
                  , "chain": "enemy4"
                }
              , "enemy4": {
                    "type": "unit"
                  , "unit": {
                        "pos": [6, 1]
                      , "character_id":-9110
                      , "icon":"shadow"
                      , "code": "ork4"
                    }
                  , "chain": "enemy5"
                }
              , "enemy5": {
                    "type": "unit"
                  , "unit": {
                        "pos": [8, 1]
                      , "character_id":-9110
                      , "icon":"shadow"
                      , "code": "ork5"
                    }
                  , "chain": "orkking_speak"
                }
              , "orkking_speak": {
                    "type": "lead"
                  , "leads": [
                        "LINES %orkking% もう逃げられないオーク！皆殺しにしろオーク！"
                      , "LINES %ork4% オーク！"
                      , "LINES %ork5% オーク！"
                    ]
                }
              , "set_out": {
                    "trigger": "rotation"
                  , "rem": "rotationキーはロジックでセットする"
                  , "type": "unit_event"
                  , "target_unit": "kosan"
                  , "event": {
                        "name": "x_set_out"
                    }
                }
              , "set_out_ork": {
                    "trigger": "rotation"
                  , "rem": "rotationキーはロジックでセットする"
                  , "type": "unit_event"
                  , "target_unit": "orkking"
                  , "event": {
                        "name": "x_set_out_ork"
                    }
                }
              , "speak1": {
                    "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "SPEAK %avatar% もじょ おお、あれはオークなのだ・・"
                      , "SPEAK %avatar% もじょ 300年前に絶滅したのだ。今はもう見れない亜人なのだ"
                      , "LINES %avatar% すごーい！初めて見た・・"
                      , "LINES %avatar% 歴史を感じるねぇ・・"
                    ]
                }
              , "speak2": {
                    "type": "lead"
                  , "leads": [
                        "LINES %kosan% はあはあ・・もうダメだ・・"
                      , "LINES %avatar% だ、大丈夫？"
                      , "LINES %kosan% 僕は古参でね。このゲームを最初からプレイしてるんだけど最近本当に敵がどんどん強くなって・・"
                      , "LINES %avatar% それがこのゲーム、ハッキングされたらしいよ"
                      , "LINES %kosan% なるほどな・・そういうことか"
                      , "LINES %kosan% 今、ゲーム内はマルティナの戦いのイベント中なんだ"
                      , "LINES %avatar% マルティナの戦い？"
                      , "SPEAK %avatar% もじょ 有名な史実なのだ"
                      , "SPEAK %avatar% もじょ その戦いに勝利して一気に人間側が有利になった決戦なのだ"
                      , "LINES %kosan% そうなんだけど、今、人間側は大苦戦だ。このままだと確実に負けるんだ。"
                      , "LINES %avatar% え？やばいじゃん。"
                      , "LINES %kosan% 敵が強くなってるからな・・。ゲームの中で誰も敵を倒せなくなってる。"
                      , "LINES %kosan% このままじゃブラックトロル王なんて誰も倒せないぞ"
                      , "SPEAK %avatar% もじょ まあ、バグ直せばそれも解決なのだ"
                      , "LINES %avatar% そうだね。さっさとコントロールセンターに行こう"
                      , "LINES %kosan% 中央のコントロールセンターに行こうにもモンスターに占拠されてて近寄れないぜ？"
                      , "LINES %avatar% な！！？？？"
                      , "SPEAK %avatar% もじょ そんな事だろうと思ったのだ・・"
                      , "LINES %kosan% この戦いに参加して亜人を追い払うしかないぞ"
                      , "LINES %avatar% まあ・・それしかないか・・"
                      , "LINES %kosan% しかし亜人の軍勢は凄まじいからな"
                      , "LINES %kosan% みんな人間を食べて黒化している"
                      , "LINES %kosan% 史実通りなら相手の将軍はブラックサイクロプスのはずだ・・"
                      , "LINES %avatar% そいつを倒してとりあえず軍勢を押し返せばいいんだね"
                      , "SPEAK %avatar% もじょ またまた骨が折れるのだ"
                    ]
                  , "chain": "goto_room1"
                }
              , "goto_room1": {
                    "type": "goto"
                  , "room": "room1"
                }
              , "_goto_room1": {
                    "condition": {"has_flag":980110009}
                  , "trigger": "hero"
                  , "pos": [6,17]
                  , "type": "goto"
                  , "room": "room1"
                  , "ornament": "goto"
                }
              , "treasure1-1": {
                    "trigger": "player"
                  , "pos": [1,13]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "ornament": "twinkle"
                  , "one_shot": 980110001
                }
              , "watar_fall1-1": {
                    "pos": [-1, 9]
                  , "ornament": "watar_fall"
                }
              , "watar_fall1-2": {
                    "pos": [11, 19]
                  , "ornament": "watar_fall"
                }
            }
        }
      , "room1": {
            "id": 98011001
          , "battle_bg": "forest"
          , "environment": "grass"
          , "start_pos": [2,9]
          , "gimmicks": {
                "start": {
                    "trigger":"rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 2"
                      , "LINES %avatar% 全滅しちゃってるね・・"
                      , "SPEAK %avatar% もじょ 死屍累々なのだ・・"
                      , "SPEAK %avatar% もじょ あ、あれはブラックゴブリンなのだ・・"
                      , "LINES %avatar% ほんとだ。真っ黒・・"
                    ]
                }
              , "enemy1-1": {
                    "trigger": "hero"
                  , "pos": [14,6], "rb": [18,13]
                  , "type": "unit"
                  , "unit": {
                        "pos": [5, 4]
                      , "character_id":-9111
                      , "icon":"shadow"
                      , "code": "bg8"
                      , "unit_class": "98011BG"
                    }
                }
              , "goto_room2": {
                    "trigger": "hero"
                  , "pos": [6,0]
                  , "type": "goto"
                  , "room": "room2"
                  , "ornament": "goto"
                }
              , "_goto_room2": {
                    "condition": {"has_flag":980110019}
                  , "trigger": "hero"
                  , "pos": [0,9]
                  , "type": "goto"
                  , "room": "room2"
                  , "ornament": "goto"
                }
              , "treasure2-1": {
                    "trigger": "player"
                  , "pos": [10,7]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980110011
                }
              , "treasure2-2": {
                    "trigger": "player"
                  , "pos": [17,19]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure2-3": {
                    "trigger": "player"
                  , "pos": [3,19]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980110012
                }
              , "treasure2-4": {
                    "trigger": "player"
                  , "pos": [12,8]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure2-5": {
                    "trigger": "player"
                  , "pos": [3,4]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "watar_fall2-1": {
                    "pos": [-1, 1]
                  , "ornament": "watar_fall"
                }
              , "watar_fall2-2": {
                    "pos": [19, 1]
                  , "ornament": "watar_fall"
                }
              , "watar_fall2-3": {
                    "pos": [15, 21]
                  , "ornament": "watar_fall"
                }
              , "watar_fall2-4": {
                    "pos": [2, 13]
                  , "ornament": "watar_fall"
                }
            }
          , "units": [
                {
                   "pos": [9,8]
                  , "character_id":-9111
                  , "code": "bg1"
                  , "icon":"shadow"
                  , "unit_class": "98011BG"
                }
              , {
                   "pos": [5,17]
                  , "character_id":-9111
                  , "code": "bg2"
                  , "icon":"shadow"
                  , "unit_class": "98011BG"
                }
              , {
                   "pos": [16,17]
                  , "character_id":-9111
                  , "code": "bg3"
                  , "icon":"shadow"
                  , "unit_class": "98011BG"
                }
              , {
                   "pos": [16,19]
                  , "character_id":-9111
                  , "code": "bg4"
                  , "icon":"shadow"
                  , "unit_class": "98011BG"
                }
              , {
                   "pos": [17,6]
                  , "character_id":-9111
                  , "code": "bg5"
                  , "icon":"shadow"
                  , "unit_class": "98011BG"
                }
              , {
                   "pos": [4,4]
                  , "character_id":-9111
                  , "code": "bg6"
                  , "icon":"shadow"
                  , "unit_class": "98011BG"
                }
              , {
                   "pos": [6,3]
                  , "character_id":-9113
                  , "icon":"shadow2"
                  , "code": "erf1"
                  , "bgm": "bgm_bossbattle"
                  , "act_brain": "rest"
                }
            ]
        }
      , "room2": {
            "id": 98011002
          , "battle_bg": "forest"
          , "environment": "grass"
          , "sphere_bg": "cloud"
          , "start_pos": [9,26]
          , "gimmicks": {
                "start": {
                    "trigger":"rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %mars% くそっ！軍勢は衰えないな"
                      , "LINES %mars% このままでは負けてしまう"
                      , "LINES %avatar% あれ、あなたは・・"
                      , "LINES %mars% 私は軍団長をやっているマルス・マルティーニというものだ。"
                      , "LINES %avatar% うわ・・！本物だ！"
                      , "SPEAK %avatar% もじょ なかなかイケメンなのだ"
                      , "LINES %mars% むこうのコントロールセンターの所にブラックトロル王がいるんだ。"
                      , "LINES %mars% 軍勢を押し返して、相手の将軍を倒してほしい。"
                      , "LINES %avatar% あなたはどうするの？"
                      , "LINES %mars% 私はその隙にブラックトロル王を倒す！"
                      , "LINES %avatar% わ、いっきにクライマックスだ"
                      , "LINES %mars% 行くぞ！"
                      , "LINES %buka1% はっ！"
                      , "LINES %buka2% はっ！"
                    ]
	               , "chain": "mars_exit"
                }
             , "mars_exit": {
                   "type": "unit_exit"
                 , "exit_target": "mars"
	               , "chain": "buka1_exit"
               }
             , "buka1_exit": {
                   "type": "unit_exit"
                 , "exit_target": "buka1"
	               , "chain": "buka2_exit"
               }
             , "buka2_exit": {
                   "type": "unit_exit"
                 , "exit_target": "buka2"
	               , "chain": "speak2-1"
               }
              , "speak2-1": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% よし、ボクたちも行くか！"
                    ]
                }
              , "hero_surprise": {
                    "type": "lead"
                  , "ignition": {"!has_memory":"check1_pass"}
                  , "memory_on": "check1_pass"
                  , "leads": [
                        "LINES %avatar% アチチ！！"
                      , "SPEAK %avatar% もじょ 除草用の火炎瓶なのだ"
                    ]
                }
              , "ork_supply": {
                    "type": "unit"
                  , "lasting": 99
                  , "unit": {
                        "pos": [7,15]
                      , "character_id":-9112
                      , "items": [-3996,-3996,-3996,-3996,-3996,-3996,-3996]
                      , "icon":"shadow"
                    }
                }
              , "enemy2-1": {
                    "trigger": "hero"
                  , "pos": [15,15], "rb": [18,21]
                  , "type": "unit"
                  , "unit": {
                        "pos": [17, 15]
                      , "character_id":-9113
                      , "icon":"shadowB"
                    }
                }
              , "enemy2-2": {
                    "trigger": "hero"
                  , "pos": [13,11], "rb": [18,12]
                  , "type": "unit"
                  , "unit": {
                        "pos": [17, 8]
                      , "character_id":-9113
                      , "icon":"shadowB"
                    }
                }
              , "enemy2-4": {
                    "trigger": "hero"
                  , "pos": [6,8], "rb": [13,9]
                  , "type": "unit"
                  , "unit": {
                        "pos": [7, 12]
                      , "character_id":-9114
                      , "icon":"shadow2"
                      , "bgm": "bgm_bossbattle"
                    }
	               , "chain": "speak2-2"
                }
              , "speak2-2": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% 新手？"
                      , "SPEAK %avatar% もじょ ブラックラミアなのだ"
                    ]
                }
              , "enemy2-5": {
                    "trigger": "hero"
                  , "pos": [5,10], "rb": [10,13]
                  , "type": "unit"
                  , "unit": {
                        "pos": [3, 7]
                      , "character_id":-9114
                      , "icon":"shadow2"
                      , "bgm": "bgm_bossbattle"
                    }
                }
              , "speak2-3": {
                    "trigger": "hero"
                  , "pos": [5,3], "rb": [11,6]
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% あっ！あいつが敵の将軍かな？"
                      , "SPEAK %avatar% もじょ サイクロプスなのだ・・。めちゃくちゃ強いのだ・"
                      , "LINES %avatar% んっ？また誰か来た！"
                    ]
	               , "chain": "king_appear"
                }
              , "king_appear": {
                    "type": "unit"
                  , "unit": {
                        "pos": [9, 3]
                      , "code":"king"
                      , "character_id":-9122
                      , "icon":"shadowG"
                    }
	               , "chain": "king_speaks"
                }
              , "king_speaks": {
                    "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "UALGN %boss% 3"
                      , "LINES %boss% 王！ハハー！"
                      , "LINES %avatar% 王？あれがブラックトロル王・・"
                      , "SPEAK %avatar% もじょ そうっぽいのだ・・"
                      , "LINES %king% サイクロプスよ！人間の奴らが随分盛り返してきているようだ。"
                      , "LINES %boss% ハハッ！しかし問題ありません"
                      , "LINES %king% そうか？そこに一匹、ネズミがいるようだぞ？"
                      , "UALGN %boss% 0"
                      , "SPEAK %avatar% もじょ バレたのだ！"
                      , "LINES %avatar% バレちゃしょーがないわね"
                      , "LINES %avatar% あなたがブラックトロル王？"
                      , "LINES %king% フッ・・いかにもだが・・"
                      , "LINES %king% なんだ貴様は・・ムッ・・！？"
                      , "LINES %avatar% えっ・・？"
                      , "LINES %king% お前は・・"
                      , "LINES %avatar% ？？？"
                      , "LINES %king% ・・ほう・・そうか・・そういうことか・・"
                      , "LINES %avatar% な・・なによ・・"
                      , "LINES %king% こやつはわしが直々に相手する"
                      , "LINES %king% サイクロプスよ。下がっているがよい！"
                      , "LINES %boss% ハハッ！"
                      , "SPEAK %avatar% もじょ ぐっ・・勝てるわけないのだ・・"
                      , "NOTIF 待ていっ！"
                      , "LINES %king% むっ？誰奴！"
                      , "UALGN %boss% 2"
                      , "UALGN %king% 2"
                      , "UALGN %avatar% 2"
                    ]
	               , "chain": "mars_appear"
                }
              , "mars_appear": {
                    "type": "unit"
                  , "unit": {
                        "pos": [11, 4]
                      , "code":"mars2"
                      , "character_id":-9121
                      , "union": 1
                      , "align": 1
                      , "icon":"man"
                    }
	               , "chain": "speak2-4"
                }
              , "speak2-4": {
                    "type": "lead"
                  , "leads": [
                        "LINES %mars2% 待て！お前は俺が相手だ！"
                      , "LINES %avatar% マルティーニさん！"
                      , "LINES %king% ほう・・。大将自ら来たか・・。"
                      , "LINES %mars2% 王よ・・人間と亜人もももうあまりにも長く戦っているな・・"
                      , "LINES %king% そうだな・・もう100年か・・？"
                      , "LINES %mars2% ・・そろそろ決着をつけないか？"
                      , "LINES %king% 大した自信だな・・よかろう・・！"
                      , "LINES %king% サイクロプスよ、そいつはお前に任せた"
                      , "LINES %boss% 御意！"
                      , "LINES %avatar% マルティーニさん！気を付けて・・"
                      , "LINES %mars2% 大丈夫だ。任せておけ！ありがとう。"
                      , "LINES %king% 場所を移すぞ。ついてこい！"
                      , "UALGN %king% 0"
                      , "LINES %king% ・・貴様、名前は・・？"
                      , "LINES %avatar% えっ・・？[NAME]だけど・・"
                      , "LINES %king% [NAME]か・・貴様の命があればまた会おう・・"
                    ]
	               , "chain": "mars_exit2"
                }
             , "mars_exit2": {
                   "type": "unit_exit"
                 , "exit_target": "mars2"
	               , "chain": "king_exit"
               }
             , "king_exit": {
                   "type": "unit_exit"
                 , "exit_target": "king"
	               , "chain": "speak2-5"
               }
              , "speak2-5": {
                    "type": "lead"
                  , "leads": [
                        "LINES %avatar% 行っちゃった"
                      , "SPEAK %avatar% もじょ また会おうって何の事なのだ・・？"
                      , "LINES %avatar% ・・さあ？"
                      , "UALGN %boss% 0"
                      , "LINES %boss% 我々も決着をつけるぞ。かかってこい！"
                    ]
                }
              , "finish": {
                    "type": "escape"
                  , "escape_result": "success"
                  , "touch": "finish_comment"
                }
              , "finish_comment": {
                     "type": "lead"
                   , "leads": [
                          "UALGN %avatar% 0"
                        , "SPEAK %avatar% もじょ これで敵は指揮系統を失ったはずなのだ"
                        , "LINES %avatar% よし、コントロールセンターに行こう！"
                        , "NOTIF 急に続く・・"
                     ]
                }
              , "treasure3-1": {
                    "trigger": "player"
                  , "pos": [4,25]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980110021
                }
              , "treasure3-2": {
                    "trigger": "player"
                  , "pos": [2,25]
                  , "type": "treasure"
                  , "item_id": 1202
                  , "ornament": "twinkle"
                  , "one_shot": 980110022
                }
              , "treasure3-4": {
                    "trigger": "player"
                  , "pos": [14,17]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure3-5": {
                    "trigger": "player"
                  , "pos": [13,11]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure3-6": {
                    "trigger": "player"
                  , "pos": [2,8]
                  , "type": "treasure"
                  , "lv_recv": true
                  , "ornament": "twinkle"
                }
              , "treasure3-7": {
                    "trigger": "player"
                  , "pos": [17,23]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "ornament": "twinkle"
                  , "one_shot": 980110023
                }
              , "treasure3-8": {
                    "trigger": "player"
                  , "pos": [14,25]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "ornament": "twinkle"
                  , "one_shot": 980110024
                }
              , "hide-treasure1": {
                    "trigger": "player"
                  , "pos": [8,23]
                  , "type": "treasure"
                  , "item_id": 1902
                  , "ornament": "twinkle"
                  , "one_shot": 980110025
                }
              , "hide-treasure2": {
                    "trigger": "player"
                  , "pos": [10,15]
                  , "type": "treasure"
                  , "lv_eqp": true
                  , "ornament": "twinkle"
                  , "one_shot": 980110026
                }
              , "treasure3-9": {
                    "trigger": "player"
                  , "pos": [13,6]
                  , "type": "treasure"
                  , "item_id": 1905
                  , "ornament": "twinkle"
                  , "one_shot": 980110027
                }
              , "watar_fall3-1": {
                    "pos": [2, 12]
                  , "ornament": "watar_fall"
                }
              , "watar_fall3-2": {
                    "pos": [15, 26]
                  , "ornament": "watar_fall"
                }

            }
          , "units": [
                {
                   "pos": [9,25]
                  , "code":"mars"
                  , "character_id":-9121
                  , "union": 1
                  , "icon":"man"
                }
              , {
                   "pos": [8,24]
                  , "code":"buka1"
                  , "character_id":-9908
                  , "icon":"man"
                  , "union": 1
               }
              , {
                   "pos": [10,24]
                  , "code":"buka2"
                  , "character_id":-9908
                  , "icon":"man"
                  , "union": 1
                }
              , {
                   "pos": [9,4]
                  , "character_id":-9115
                  , "code":"boss"
                  , "icon":"shadowG"
                  , "bgm": "bgm_bigboss"
                  , "act_brain": "rest"
                  , "trigger_gimmick": "finish"
                }
              , {
                   "pos": [7,20]
                  , "character_id":-9112
                  , "items": [-3996,-3996,-3996,-3996,-3996,-3996,-3996]
                  , "icon":"shadow"
                }
              , {
                   "pos": [5,18]
                  , "character_id":-9112
                  , "items": [-3996,-3996,-3996,-3996,-3996,-3996,-3996]
                  , "icon":"shadow"
                }
            ]
        }
    }
}
