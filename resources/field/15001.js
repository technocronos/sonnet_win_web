{
    "extra_uniticons": ["boy", "man", "woman", "shisyou", "uncle"]
  , "rooms": {
        "start": {
            "id": 15001000
          , "battle_bg": "dungeon"
          , "environment": "grass"
          , "bgm": "bgm_home"
          , "start_pos": [0,7]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"cleared":false}
                  , "ignition": {"!has_flag":1500100001}
        				  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "one_shot": 1500100001
                  , "leads": [
                        "LINES %avatar% ただいま～。故郷の村に到着！"
                      , "SPEAK %avatar% もじょ 相変わらずちっさい村なのだ"
                      , "LINES %avatar% この島で唯一の村だからねぇ"
                      , "SPEAK %avatar% もじょ 人に適当に近づくと話を聞けるのだ"
                      , "SPEAK %avatar% もじょ ここでいろいろ情報収集なのだ"
                      , "SPEAK %avatar% もじょ ちなみにこのクエはAP消費なしなのだ"
                      , "SPEAK %avatar% もじょ ゴールはないからやめたきゃ好きな時に中断するのだ"
                      , "LINES %avatar% だれに話してんの？"
                      , "SPEAK %avatar% もじょ ほっとけなのだ"
                    ]
                }
              , "open_comment2": {
                    "ignition": {
                        "cleared":false ,
                        "11005_cleared":true ,
                        "14003_cleared":true ,
                        "15004_cleared":false ,
                        "!has_flag":1500100003
                    }
        				  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% ただいま～。故郷の村に到着！"
                      , "SPEAK %avatar% もじょ とりあえずポポタに金もらいに行くのだ"
                    ]
                }
              , "treasure1": {
                    "trigger": "player"
                  , "pos":[18,1]
                  , "type": "treasure"
                  , "item_id": 1001
                  , "one_shot": 1500100010
                  , "ornament": "twinkle"
                }
              , "old1_speak": {
                    "trigger": "hero"
                  , "pos": [5,3], "rb": [8,5]
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %avatar% あ、神父さま。おはよー"
                      , "SPEAK %old1% 神父 おお[NAME]。ガラフのじいさんは元気かの？"
                      , "LINES %avatar% 元気すぎだよ…"
                      , "SPEAK %old1% 神父 ホッホッ。そりゃあいい。さすがマルティーニの出身。ボケとるヒマもないのぅ"
                      , "SPEAK %avatar% もじょ 多少ボケたほうがちょうどいいのだ、あのジジィは"
                      , "SPEAK %old1% 神父 あのじいさんにたまにはお祈りに来いと言っておいてくれ"
                      , "LINES %avatar% はーい。"
                      , "SPEAK %old1% 神父 じぃさんの息子夫婦は元気かのぅ・・マルティーニに住んどるらしいが…"
                      , "SPEAK %old1% 神父 しかし孫は嫁さん連れてこの島に来たわけじゃし、あのじいさんも老後は安泰じゃの"
                    ]
                }
              , "woman1_speak": {
                    "trigger": "hero"
                  , "pos": [7,7], "rb": [7,8]
                  , "type": "lead"
                  , "leads": [
                        "LINES %woman1% あら[NAME]。こんにちは"
                    ]
                }
              , "man1_speak": {
                    "trigger": "hero"
                  , "pos": [5,10], "rb": [8,12]
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 0"
                      , "UALGN %man1% 3"
                      , "LINES %man1% よう[NAME]。暑くなってきたな。そろそろ海で泳ぎたいもんだ。"
                      , "LINES %avatar% ツクール海岸ちょっと遠いんだよね・・"
                      , "LINES %man1% 他は岩山だからなぁ。西の森は暗くてモンスターの巣だし南はヌメールの湿原だし"
                      , "SPEAK %avatar% もじょ 遊べる所はちーともないのだ"
                      , "LINES %man1% そうそう知ってるかい？"
                      , "LINES %man1% ツクール海岸は引き潮になると岩場に洞窟が現れるのさ。"
                      , "LINES %man1% そこから出てきた魔物がうようよし始めるから引き潮の時は近づかない方がいいぞ。"
                    ]
                }
              , "man2_speak": {
                    "trigger": "hero"
                  , "pos": [13,3], "rb": [16,5]
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %man2% よう[NAME]。"
                      , "LINES %man2% 向かいの家がじいさんの孫夫婦の家だよ。"
                      , "LINES %man2% でも最近、何か仲が悪くてね・・。"
                      , "LINES %man2% ま、若い証拠かねぇ"

                    ]
                }
              , "popota_speak": {
                    "ignition": {
                        "11005_cleared":false ,
                        "14003_cleared":false ,
                        "15004_cleared":false ,
                        "!has_flag":1500100003
                    }
                  , "trigger": "hero"
                  , "pos": [13,10], "rb": [16,13]
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 0"
                      , "UALGN %popota% 3"
                      , "SPEAK %popota% ポポタ あっ、[NAME]。またじいちゃんの生活費？"
                      , "LINES %avatar% い・・いや・・。違うんだけど"
                      , "LINES %avatar% 何やってんの？"
                      , "UALGN %popota% 2"
                      , "SPEAK %marie% マリー ・・・"
                      , "SPEAK %popota% ポポタ マリー・・機嫌直しておくれ"
                      , "SPEAK %marie% マリー もう知らないっ！こんな島退屈だし！マルティーニに帰る！"
                      , "SPEAK %popota% ポポタ そんな～。結婚するときは「こんなスローライフもいいわね」とか言ってたじゃないか～"
                      , "LINES %avatar% マリーさん、どうしたの？"
                      , "UALGN %popota% 3"
                      , "SPEAK %popota% ポポタ いや、ちょっとまたケンカして・・ハハハ・・"
                      , "SPEAK %avatar% もじょ とっとと別れちまえなのだ"
                      , "LINES %avatar% もじょ！"
                      , "SPEAK %popota% ポポタ いや～そういう強情な所もかわいいというか・・ハハ・・ハハハ・・"
                      , "SPEAK %avatar% もじょ まさに犬も食わないのだ"
                    ]
                }
              , "popota_speak1.5": {
                    "ignition": {
                        "11005_cleared":true ,
                        "14003_cleared":false ,
                        "15004_cleared":false ,
                        "!has_flag":1500100003
                    }
                  , "trigger": "hero"
                  , "pos": [13,10], "rb": [16,13]
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 0"
                      , "UALGN %popota% 3"
                      , "SPEAK %popota% ポポタ あっ、[NAME]。またじいちゃんの生活費？"
                      , "LINES %avatar% ・・・"
                      , "SPEAK %popota% ポポタ どうしたの？"
                      , "LINES %avatar% いえ、なんでもないです"
                      , "LINES %avatar% もじょ！海岸いこ！"
                    ]
                }
              , "popota_speak2": {
                    "ignition": {
                        "11005_cleared":true ,
                        "14003_cleared":true ,
                        "15004_cleared":false ,
                        "!has_flag":1500100003
                    }
                  , "trigger": "hero"
                  , "pos": [13,10], "rb": [16,13]
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 0"
                      , "UALGN %popota% 3"
                      , "SPEAK %popota% ポポタ あっ、[NAME]。またじいちゃんの生活費？"
                      , "LINES %avatar% あ、あのね…じつは師匠が牧場でアテにしてた収入がなくなっちゃって…"
                      , "SPEAK %avatar% もじょ おまけに飲むわ打つわ買うわで金がスッカラカンになったのだ"
                      , "SPEAK %popota% ポポタ いや～牧場のことは聞いてるよ。しょうがないよね・・"
                      , "SPEAK %popota% ポポタ とりあえず、これくらいでしのげるだろう…"
                      , "LINES %avatar% あ、ありがとうございます！"
                      , "LINES %avatar% …なんか元気ないね。そういやマリーさんは・・？"
                      , "SPEAK %avatar% もじょ とうとう出て行ったのだ？"
                      , "SPEAK %popota% ポポタ いや…なんか川辺にシロ貝採りに行ってたときに結婚指輪なくしたらしくってさ"
                      , "SPEAK %popota% ポポタ 朝から指輪探してくるって言って、ずっと帰ってこないんだよ"
                      , "SPEAK %popota% ポポタ もうすぐ日が暮れるからさ、さすがに心配になってきてね…"
                      , "SPEAK %avatar% もじょ 指輪探しなんてウソなのだ。愛想尽かして実家に帰ったのだ"
                      , "SPEAK %popota% ポポタ ええ！？そんなまさか！いや、でも…"
                      , "LINES %avatar% もじょ！"
                      , "LINES %avatar% でもたしかにちょっと心配になってきた"
                      , "SPEAK %avatar% もじょ ヌメール湿原の方に行ってるんじゃないのだ？"
                      , "SPEAK %avatar% もじょ だとしたらアウトなのだ。超凶暴なモンスターがうようよいるのだ"
                      , "SPEAK %popota% ポポタ ま・・まさか・・。マ、マリー！！！！"
                      , "LINES %avatar% ちょ、ちょっと探してくるね"
                    ]
                    , "chain": "goal"
                }
              , "popota_speak4": {
                    "ignition": {
                        "11005_cleared":true ,
                        "14003_cleared":true ,
                        "15004_cleared":true ,
                        "has_flag":1500100003
                    }
                  , "trigger": "hero"
                  , "pos": [13,10], "rb": [16,13]
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 0"
                      , "UALGN %popota% 3"
                      , "SPEAK %popota% ポポタ あっ、[NAME]。こないだはありがとう！"
                      , "SPEAK %marie% マリー ・・・"
                      , "LINES %avatar% マリーさん、どうしたの？"
                      , "SPEAK %popota% ポポタ いや、ちょっとまたケンカして・・ハハハ・・"
                      , "SPEAK %avatar% もじょ まさに犬も食わないのだ"
                    ]
                }
              , "escape": {
                    "trigger": "hero"
                  , "pos": [0,8]
                  , "type": "escape"
                  , "escape_result": "escape"
                  , "ornament": "escape"
                }
              , "escape2": {
                    "trigger": "hero"
                  , "pos": [18,8]
                  , "type": "escape"
                  , "escape_result": "escape"
                  , "ornament": "escape"
                }
              , "goal": {
                    "type": "escape"
                  , "ignition": {"!has_flag":1500100002}
                  , "one_shot": 1500100002
                  , "escape_result": "success"
                }
            }
          , "units": [
                {
                    "character_id": -9906
                  , "code": "old1"
                  , "icon":"shisyou"
                  , "pos": [7,3]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "man1"
                  , "pos": [6,12]
                  , "icon":"uncle"
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "man2"
                  , "icon":"man"
                  , "pos": [15,3]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "popota"
                  , "icon":"man"
                  , "align": 2
                  , "pos": [13,12]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "woman1"
                  , "icon":"woman"
                  , "pos": [8,8]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "condition": {
                        "|14003_cleared":false , 
                        "|15004_cleared":true 
                      }
                  , "character_id": -9906
                  , "code": "marie"
                  , "align": 2
                  , "icon":"woman"
                  , "pos": [15,12]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
    }
}
