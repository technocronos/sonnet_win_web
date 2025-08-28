{
    "extra_uniticons": ["man", "woman", "shisyou", "boy", "uncle"]
   ,"rooms": {
        "start": {
            "id": 98010000
          , "battle_bg": "forest"
          , "environment": "grass"
          , "bgm": "bgm_home"
          , "start_pos": [1, 11]
          , "gimmicks": {
                "start": {
                    "trigger":"rotation"
                  , "rotation":1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% はあ・・はあ・・ようやくついた"
                      , "SPEAK %avatar% もじょ じじいはさっさと船に行ってるのだ"
                      , "LINES %avatar% 薄情だなぁ・・"
                      , "SPEAK %avatar% もじょ でっかい船がとまってるのだ"
                      , "LINES %avatar% 間近で見るのはじめてかも・・"
                      , "SPEAK %avatar% もじょ さっさと船に行って荷物届けるのだ"
                    ]
                }
              , "boy1_speak": {
                    "trigger": "hero"
                  , "pos": [1,8], "rb": [3,9]
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %boy1% ・・・"
                      , "LINES %avatar% あれ？なにやってるの？"
                      , "LINES %boy1% ・・・。ゲーム。"
                      , "LINES %avatar% これがゲーム？みせてみせて!"
                      , "LINES %boy1% こんなの糞ゲーだよ。全然面白くない"
                      , "LINES %avatar% え？そうなの？"
                      , "LINES %boy1% レビューに書き込んでやる！"
                      , "LINES %boy1% 「こんなつまらないゲームさっさと運営やめちまえ」っと・・"
                      , "SPEAK %avatar% もじょ 何かイライラしてるのだ・・"
                      , "LINES %boy1% 敵がいきなりめちゃくちゃ強くなってさ"
                      , "LINES %boy1% すっごい意地悪ばっかしてくるんだ"
                      , "LINES %avatar% へー・・"
                      , "LINES %avatar% まあ、ほどほどにね"
                    ]
                }
              , "woman1_speak": {
                    "trigger": "hero"
                  , "pos": [1,4], "rb": [3,5]
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %woman1% はあ・・みんな船の中で海も見ないでゲームばっか嫌になっちゃう・・"
                      , "LINES %woman1% さっさとこんなゲーム無くなればいいのに"
                    ]
                }
              , "man1_speak": {
                    "trigger": "hero"
                  , "pos": [7,9], "rb": [10,12]
                  , "type": "lead"
                  , "leads": [
                        "LINES %man1% ・・・"
                      , "LINES %avatar% この人もゲームかな？"
                      , "SPEAK %avatar% もじょ そうっぽいのだ・・"
                      , "LINES %man1% このクソゲーめ！レビューに書き込んでやる！"
                      , "LINES %man1% 「なるほど納得の★１ですね。こんなクソゲー初めてです。」っと・・"
                      , "SPEAK %avatar% もじょ なんかこわいのだ・・"
                      , "LINES %avatar% みんななんかイライラしてるね・・"
                    ]
                }
              , "boy2_speak": {
                    "trigger": "hero"
                  , "pos": [11,7], "rb": [12,8]
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 2"
                      , "LINES %boy2% ・・・"
                      , "LINES %avatar% この子もゲームかな？"
                      , "SPEAK %avatar% もじょ そうっぽいのだ・・"
                      , "LINES %avatar% 流行ってるなぁ"
                      , "LINES %boy2% このクソゲーめ！レビューに書き込んでやる！"
                      , "LINES %boy2% 「全くもってやる必要のない、面白みのないゲームです。時間の無駄でした」っと・・"
                      , "SPEAK %avatar% もじょ なんかこわいのだ・・"
                      , "LINES %avatar% ねえ、このゲームってどういう内容なの？"
                      , "LINES %boy2% ブラック・トロル・クエストっていうんだ"
                      , "SPEAK %avatar% もじょ ブラック・トロル・クエスト？・・まさか"
                      , "LINES %boy2% うん。300年前の戦争が題材のゲームなんだって"
                      , "LINES %boy2% ブラックトロルの王を倒して世界を救うんだ"
                      , "LINES %boy2% 史実に忠実に作ってあるんだってさ"
                      , "LINES %boy2% みんな略してブラトロって呼んでるけど"
                      , "LINES %avatar% はえー・・ブラトロねぇ・・歴史ゲームかぁ・・"
                      , "SPEAK %avatar% もじょ なんかちょっと面白そうなのだ"
                    ]
                }
              , "man2_speak": {
                    "trigger": "hero"
                  , "pos": [8,4], "rb": [10,5]
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 3"
                      , "LINES %man2% ・・・"
                      , "LINES %avatar% この人もゲームかな？"
                      , "SPEAK %avatar% もじょ そうっぽいのだ・・"
                      , "LINES %man2% このクソゲーめ！レビューに書き込んでやる！"
                      , "LINES %man2% 「世界初の霊子ゲームと聞いて期待しましたがこんな酷い出来とは思いませんでした」っと・・"
                      , "SPEAK %avatar% もじょ ・・霊子ゲーム？なんなのだそれは"
                      , "LINES %man2% 霊子の力を利用することでものすごい処理能力を可能とすることができるって触れ込みでね"
                      , "LINES %man2% 充電も要らないし、どこでも通信できてすごく便利なんだ"
                      , "LINES %man2% だけどこんな内容じゃなぁ・・"
                      , "LINES %avatar% もじょ、霊子力ってなに？"
                      , "SPEAK %avatar% もじょ ソネットの源の力なのだ。亜人が持っているのだ。"
                      , "LINES %avatar% まさか・・そんなものを使って動かしてるとは・・"
                      , "LINES %avatar% 亜人からどうやって霊子力を取ってるの？"
                      , "SPEAK %avatar% もじょ まあ知らんけどどうせロクな方法じゃないのだ"
                      , "SPEAK %avatar% もじょ マルティーニの亜人狩りは有名なのだ"
                      , "SPEAK %avatar% もじょ そんなことして抽出された霊子にどんな怨念が混ざってるか分からんのだ。"
                      , "LINES %avatar% 人間が亜人を倒すゲームを亜人の怨念で動かしてるってわけか・・"
                      , "SPEAK %avatar% もじょ 一気にキナ臭くなってきたのだ・・"
                    ]
                }
              , "dev_speak": {
                    "trigger": "hero"
                  , "pos": [10,1]
                  , "type": "lead"
                  , "leads": [
                        "UALGN %avatar% 1"
                      , "UALGN %dev% 2"
                      , "LINES %dev% うーん・・。困った・・"
                      , "LINES %avatar% どうしたの？"
                      , "LINES %dev% 私はゲームクリエイターだ。ゲームを作ってるんだ"
                      , "SPEAK %avatar% もじょ みんなやってるあのゲーム、お前が作ったのだ？"
                      , "LINES %dev% ああ・・そうなんだけど、どうも変だ・・"
                      , "LINES %dev% 何かそんなプログラム入れていないはずなのにゲームがおかしいんだ・・"
                      , "SPEAK %avatar% もじょ バグってるんじゃないのだ？お前の能力不足なのだ。"
                      , "LINES %dev% いや・・そうじゃないんだ・・"
                      , "LINES %dev% 敵キャラが嫌がらせみたいなことばっかりプレイヤーにするんだ・・"
                      , "LINES %dev% そんなプログラムは入れてないんだが・・。"
                      , "LINES %dev% そのせいでユーザーがイライラしてしまって評価がどんどん下がってしまっている"
                      , "LINES %avatar% たしかに・・みんなすごく口悪いねぇ・・"
                      , "SPEAK %avatar% もじょ まるでこのゲームがヘイトを進んで集めているかのようなのだ"
                      , "LINES %dev% ヘイトが集まれば集まるほどどんどん敵が強くなっているようなんだ"
                      , "LINES %dev% ・・実はこのゲームを巡って国の若者が殺伐としててね。"
                      , "LINES %dev% 国からヘイトを煽るようなゲームを即刻停止せよとお達しが来てるんだよ・・"
                      , "LINES %dev% でないと処罰すると・・"
                      , "LINES %dev% だけどもこのゲームはもはや、停止コマンドすら受け付けてくれない・・"
                      , "LINES %dev% 私はもうダメだ・・どうしたらいいのか分からない・・"
                      , "SPEAK %avatar% もじょ お前が金欲しさに変なもん作るからなのだ"
                      , "SPEAK %avatar% もじょ 自業自得なのだ"
                      , "LINES %dev% そうじゃないんだよ・・ゲームを通して感動を伝えたかったんだ・・"
                      , "LINES %avatar% もじょ、なんとかならない？"
                      , "SPEAK %avatar% もじょ そんなん知らんのだ"
                      , "LINES %dev% 実はこのゲーム、同じ霊子力を持つ者ならゲームの中に入ることができるんだ"
                      , "LINES %dev% 中から停止コマンドを叩くだけなんだけどね"
                      , "LINES %dev% ソネットを扱えて協力してくれる人間は少なくてね"
                      , "LINES %dev% それである人を訪ねてこの島に来たんだよ"
                      , "SPEAK %avatar% もじょ ふーん。誰を当てにして来たのだ？"
                      , "LINES %dev% ガラフ・ラターニ師はご存じかい？"
                      , "LINES %avatar% ぶっ！シショーじゃん"
                      , "SPEAK %avatar% もじょ じじいのことなのだ・・"
                      , "LINES %dev% あの方は素晴らしい力と人格をお持ちと聞いてね"
                      , "LINES %avatar% ぶっ！素晴らしい人格？？"
                      , "SPEAK %avatar% もじょ どういう伝わり方してるのだ・・"
                      , "LINES %avatar% あんなんでもマルティーニじゃ少しは尊敬されてんのかなぁ・・"
                      , "SPEAK %avatar% もじょ 信じられんのだ・・"
                      , "LINES %dev% どこにいるか分からないかな？"
                      , "SPEAK %avatar% もじょ ああ・・じじいなら多分船の中に・・"
                      , "LINES %avatar% 残念・・入れ違いです。今マルティーニに行っちゃってます"
                      , "LINES %dev% え？？なんてことだ・・。時間がないのに"
                      , "LINES %avatar% あの・・ボク、弟子なんですけどボクが入って解決しましょうか？"
                      , "SPEAK %avatar% もじょ まーた首つっこもうとしてるのだ・・"
                      , "LINES %dev% え？君がかい・・？まあ特に危険があることはないが・・"
                      , "LINES %avatar% ゲームもどういうのか興味あるし・・"
                      , "LINES %avatar% 中覗いてみたいじゃん！"
                      , "SPEAK %avatar% もじょ やれやれなのだ・・"
                      , "LINES %dev% よし！じゃ、頼んだよ"
                    ]
                   , "chain": "tresD"
                }
              , "tresD": {
                    "trigger":"hero"
                  , "pos":[10, 1]
                  , "type": "drama"
                  , "drama_id": 9801001
                  , "chain": "goal"
                }
              , "street_light1": {
                    "pos": [0, 3]
                  , "ornament": "street_light"
                }
              , "bench1": {
                    "pos": [2, 3]
                  , "ornament": "bench1"
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
            }
          , "units": [
                {
                    "character_id": -9906
                  , "code": "boy1"
                  , "pos": [2,7]
                  , "icon":"boy"
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
                    "character_id": -9906
                  , "code": "boy2"
                  , "align": 1
                  , "pos": [13,7]
                  , "icon":"boy"
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
                    "character_id": -9906
                  , "code": "woman1"
                  , "pos": [2,3]
                  , "icon":"woman"
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
                    "character_id": -9906
                  , "code": "man1"
                  , "pos": [9,11]
                  , "icon":"man"
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
                    "character_id": -9906
                  , "code": "man2"
                  , "pos": [9,3]
                  , "icon":"man"
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
              , {
                    "character_id": -9906
                  , "code": "dev"
                  , "pos": [9,1]
                  , "icon":"uncle"
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
    }
}
