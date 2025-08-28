{
    "extra_uniticons": ["boy", "man", "woman", "shisyou", "layla"]
  , "rooms": {
        "start": {
            "id": 41009000
          , "battle_bg": "dungeon"
          , "bgm": "bgm_registance"
          , "environment": "cave"
          , "start_pos": [7,9]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% うわぁ、ここがアジトか・・。"
                      , "SPEAK %avatar% もじょ 結構キレイなのだ。"
                      , "UALGN %avatar% 3"
                      , "LINES %gebaru% レジスタンスのアジトへようこそ！"
                      , "LINES %gebaru% やあ、しかし本当にありがとう！助かったよ！！"
                      , "LINES %avatar% い、いえ！！お役に立てましてなんというか・・"
                      , "SPEAK %avatar% もじょ ・・・。なーに照れてるのだ・・"
                      , "LINES %avatar% るっさいなあ・・"
                      , "LINES %gebaru% 改めてぜひ我々と行動を共にして欲しい。"
                      , "LINES %layla% 私からもお願いするわいっしょに亜人と人間の共生のためにがんばりましょ。"
                      , "LINES %avatar% そ、そうですね・・ボクとしましても是非・・"
                      , "LINES %gebaru% われわれの目的は二つある"
                      , "LINES %gebaru% まずは、捕まった仲間の救出だ。"
                      , "LINES %gebaru% レジスタンスは捕まると流刑地に流されてしまう。"
                      , "LINES %gebaru% 流刑地とマルティーニ朝の連絡船を襲って仲間を救出してるんだ。"
                      , "LINES %avatar% へー。それでマルティーニから海賊とかいわれてるんだ。"
                      , "LINES %gebaru% そうだなだが海賊といわれるならそれで結構だ"
                      , "LINES %gebaru% 大体、海賊なんてなんだかかっこいいしな！"
                      , "LINES %gebaru% かえってやる気になるさ！ハ、ハ、ハ・・。"
                      , "LINES %avatar% は、は、は・・・。で、ですかねー・・。"
                      , "SPEAK %avatar% もじょ こーんな軽い男のどーこがいいのだ・・"
                      , "LINES %gebaru% もう一つはもちろんマルティーニの転覆だが"
                      , "LINES %gebaru% マルティーニの力の源泉は霊子力研究所にあるんだ。"
                      , "LINES %avatar% あーそれはマルティーニでもよく聞きましたね。"
                      , "LINES %avatar% みんな自慢げに話してますね。"
                      , "LINES %gebaru% だろう？あそこを叩けば捕まっている亜人を救出できるし一石二鳥なんだ"
                      , "LINES %gebaru% しかし、それには諜報活動が欠かせない"
                      , "LINES %gebaru% レイラは俺のボディガードもやってるから諜報活動がままならない"
                      , "LINES %gebaru% そこでだ！これからは[NAME]君をボディガードとしてだな・・"
                      , "UMOVE %avatar% 2"
                      , "LINES %avatar% え？ぼでぃがーど！！"
                      , "LINES %avatar% すごいお近づきになれちゃうじゃん！！"
                      , "UALGN %layla% 1"
                      , "LINES %layla% ゲバル、私は大丈夫よ。"
                      , "UALGN %gebaru% 2"
                      , "LINES %gebaru% レイラ、君にはいつも危ない思いをさせてすまないと思っている・・"
                      , "LINES %avatar% ・・・？"
                      , "LINES %layla% ううん、でも離れ離れになるなんて・・私・・。"
                      , "LINES %avatar% ・・・。"
                      , "LINES %gebaru% 俺だってつらいさ・・だが、わかってほしい・・"
                      , "LINES %avatar% ・・・・。"
                      , "LINES %gebaru% 俺もいつも恋人の君に守ってもらうのも気が引けてしまうんだ・・。"
                      , "LINES %avatar% ・・・・・・・！！！"
                      , "LINES %gebaru% だが！離れていても心は常に君のそばに・・"
                      , "LINES %avatar% ・・・(TдT)"
                      , "SPEAK %avatar% もじょ ・・・ま、予想通りの展開なのだ。"
                      , "LINES %gebaru% しかし、これからは大丈夫だ[NAME]君が俺の身はしっかりと・・"
                      , "UMOVE %avatar% 888"
                      , "UALGN %gebaru% 0"
                      , "UALGN %layla% 0"
                      , "LINES %gebaru% あ、あれ？どうした？？"
                      , "LINES %avatar% ・・すいません。ボク、やっぱり旅を続けます・・。"
                      , "LINES %gebaru% え？ちょっ、どうしてだい？？"
                      , "LINES %avatar% ボクも旅の途中なんです・・亜人の大陸にいかなきゃ・・"
                      , "LINES %avatar% じゃ、失礼します・・"
                      , "LINES %layla% ま、待って！！"
                      , "UMOVE %layla% 8"
                      , "LINES %avatar% ・・二人ともお幸せにね・・ふふ・・ふふふ・・・"
                      , "LINES %gebaru% ど、どうしたんだ？急に・・"
                      , "LINES %layla% さ、さあ・・？？"
                      , "SPEAK %avatar% もじょ あ、ら、ら・・なのだ・・今回は立ち直れるのだ・・？"
                    ]
                  , "chain": "goal"
                }
              , "open_comment2": {
                    "condition": {"cleared":true, "reishi_cleared":false}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% うわぁ、ここがアジトか・・。"
                      , "SPEAK %avatar% もじょ 結構キレイなのだ。"
                      , "SPEAK %avatar% もじょ ちなみにこのクエはAP消費しないのだ"
                      , "SPEAK %avatar% もじょ レジスタンスたちに話聞いてみるのだ"
                    ]
                }
              , "open_comment2_2": {
                    "condition": {"reishi_cleared":true}
                  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% 誰もいない・・"
                      , "LINES %avatar% みんな死んじゃったね・・"
                    ]
                }
              , "gebaru_speak": {
                    "condition": {"reishi_cleared":false}
                  , "trigger": "hero"
                  , "pos": [7,8]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %gebaru%"
                      , "LINES %gebaru% レジスタンスのアジトへようこそ！"
                      , "LINES %gebaru% 俺がリーダーのゲバルだ"
                      , "LINES %gebaru% ゆっくりしていってくれ！"
                    ]
                }
              , "layla_speak": {
                    "condition": {"reishi_cleared":false}
                  , "trigger": "hero"
                  , "pos": [8,8]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %layla%"
                      , "LINES %layla% 諜報担当のレイラよ"
                      , "LINES %layla% 今はリーダーのボディガードを主にやってるわ"
                      , "LINES %layla% よろしくね！"
                    ]
                }
              , "man1_speak": {
                    "condition": {"reishi_cleared":false}
                  , "trigger": "hero"
                  , "pos": [3,7], "rb": [5,8]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man1%"
                      , "LINES %man1% われわれの目的は二つある"
                      , "LINES %man1% まずは、捕まった仲間の救出だ。"
                      , "LINES %man1% レジスタンスは捕まると流刑地に流されてしまう。"
                      , "LINES %man1% 流刑地とマルティーニ朝の連絡船を襲って仲間を救出してるんだ。"
                      , "LINES %man1% もう一つはもちろんマルティーニの転覆だ"
                      , "LINES %man1% 一緒にがんばろう"
                    ]
                }
              , "man2_speak": {
                    "condition": {"reishi_cleared":false}
                  , "trigger": "hero"
                  , "pos": [10,4], "rb": [12,5]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man2%"
                      , "LINES %man2% 亜人間と共生できる社会を実現するのが目的だ"
                      , "LINES %man2% 争ってばかりではどちらも滅びるだけだ"
                    ]
                }
              , "old1_speak": {
                    "condition": {"reishi_cleared":false}
                  , "trigger": "hero"
                  , "pos": [0,4], "rb": [1,5]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %old1%"
                      , "SPEAK %old1% 老人 レジスタンスには亜人も多く加わっておる"
                      , "SPEAK %old1% 老人 老若男女も分け隔てなくおる。理想の世界じゃよ"
                      , "SPEAK %old1% 老人 ワシのような老いぼれが役に立つかは分からんがの"
                    ]
                }
              , "woman1_speak": {
                    "condition": {"reishi_cleared":false}
                  , "trigger": "hero"
                  , "pos": [5,4], "rb": [7,5]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %woman1%"
                      , "LINES %woman1% あら？新入り？"
                      , "LINES %woman1% 今、食糧の準備をしているの。"
                      , "LINES %woman1% 私は亜人なんだけど"
                      , "LINES %woman1% ゴブリン族だから戦闘は苦手なのよね・・"
                      , "LINES %woman1% ウゲウゲ！"
                    ]
                }
              , "woman2_speak": {
                    "condition": {"reishi_cleared":false}
                  , "trigger": "hero"
                  , "pos": [11,7], "rb": [13,8]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %woman2%"
                      , "LINES %woman2% 私はもともとマルティーニに住んでたんだけど"
                      , "LINES %woman2% 面白そうだからこっちに来ちゃったの！"
                      , "LINES %woman2% ここはスリル満点で楽しいし"
                      , "LINES %woman2% リーダーも素敵よね！"
                    ]
                }
              , "lamp1": {
                    "pos": [3, 0]
                  , "ornament": "lamp"
                }
              , "lamp2": {
                    "pos": [12, 0]
                  , "ornament": "lamp"
                }
              , "escape": {
                    "condition": {"cleared":true}
                  , "trigger": "hero"
                  , "pos": [7,11], "rb": [8,11]
                  , "type": "escape"
                  , "escape_result": "escape"
                  , "ornament": "escape"
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
            }
          , "units": [
                {
                    "condition": {"reishi_cleared":false}
                  , "character_id": -20103
                  , "code": "layla"
                  , "icon":"layla"
                  , "pos": [8,7]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
                    "condition": {"reishi_cleared":false}
                  , "character_id": -20102
                  , "code": "gebaru"
                  , "icon":"man"
                  , "pos": [7,7]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
                    "condition": {"reishi_cleared":false}
                  , "character_id": -9907
                  , "code": "man1"
                  , "icon":"man"
                  , "pos": [4,7]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
                    "condition": {"reishi_cleared":false}
                  , "character_id": -9907
                  , "code": "man2"
                  , "icon":"man"
                  , "pos": [11,4]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "condition": {"reishi_cleared":false}
                  , "character_id": -9907
                  , "code": "old1"
                  , "icon":"shisyou"
                  , "pos": [0,4]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "condition": {"reishi_cleared":false}
                  , "character_id": -9907
                  , "code": "woman1"
                  , "icon":"woman"
                  , "pos": [6,4]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "condition": {"reishi_cleared":false}
                  , "character_id": -9907
                  , "code": "woman2"
                  , "icon":"woman"
                  , "pos": [12,7]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
    }
}
