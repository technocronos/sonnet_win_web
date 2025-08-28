{
    "extra_uniticons": ["boy", "man", "woman", "shisyou"]
  , "rooms": {
        "start": {
            "id": 51001000
          , "battle_bg": "dungeon"
          , "environment": "cave"
          , "bgm": "bgm_home"
          , "start_pos": [5,7]
          , "gimmicks": {
                "open_comment": {
                    "condition": {"cleared":false}
        				  , "trigger": "rotation"
                  , "rotation": 1
                  , "type": "lead"
                  , "leads": [
                        "LINES %avatar% うわー・・ここが流刑地かぁ・・"
                      , "SPEAK %avatar% もじょ さびれてて物騒なところなのだ・・"
                      , "SPEAK %avatar% レイラ 全員が島流しにされた囚人だからね"
                      , "SPEAK %avatar% レイラ でもレジスタンスの人間も潜入してるはずだからまずは接触しましょ"
                      , "LINES %avatar% うん"
                    ]
                }
              , "man1_speak": {
                    "trigger": "hero"
                  , "pos": [9,4], "rb": [11,5]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man1%"
                      , "LINES %man1% 何だおめえは？新入りか？ここはこの島で唯一の酒場だせ。"
                      , "LINES %man1% この島には警察もいねえ。法もねえ。闇医者しかいねえ。"
                      , "LINES %man1% 仕切ってるのはギャング団さ！"
                    ]
                }
              , "woman1_speak": {
                    "trigger": "hero"
                  , "pos": [17,4], "rb": [19,5]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %woman1%"
                      , "LINES %woman1% あら、あんた新入りかい？まだ若いのに・・ここはあたいの縄張りだよ！！"
                      , "LINES %woman1% まあ、一度入ったが絶対出られないから"
                      , "LINES %woman1% 観念して長生きする方法を考えることね・・。"
                      , "LINES %woman1% え？もぐりこんだだけだって？物好きもいいとこだね・・早くお家に帰んな！！"
                    ]
                }
              , "man3_speak": {
                    "trigger": "hero"
                  , "pos": [12,7], "rb": [14,7]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man3%"
                      , "LINES %man3% おう、ねえちゃん。ちょっと付き合えよ。ぐへへ・・"
                      , "LINES %man3% おっ、逆らう気か？この野郎！・・じゃなかったこのアマ！！"
                      , "EFFEC sprk 1307"
                      , "LINES %man3% アチチ痺れる～！！なんだてめえ、ソネット使いか！！"
                      , "LINES %man3% 覚えてやがれ、この野郎！・・じゃなかった。このアマ！！"
                    ]
                }
              , "man4_speak": {
                    "trigger": "hero"
                  , "pos": [9,8], "rb": [11,9]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man4%"
                      , "LINES %man4% ここは流刑地の波止場だよ"
                      , "LINES %man4% この島じゃ殺しや伝染病で10年生きられればいい方さ"
                      , "LINES %man4% さ、行った行った！ギャングにショバ代払えないと明日から商売できないからね！"
                    ]
                }
              , "man5_speak": {
                    "condition": {"muma_cleared":false}
                  , "trigger": "hero"
                  , "pos": [17,13], "rb": [19,14]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man5%"
                      , "LINES %man5% ・・君は・・[NAME]か？"
                      , "LINES %man5% 私はレジスタンスのものだ！よく来てくれた。"
                      , "NOTIF レジスタンス支部の人間に接触した！"
                      , "LINES %man5% 大きな声では言えないがなんでもマルティーニ王朝が使っているという・・"
                      , "LINES %man5% 抜け道がこの流刑地のどこかにあると噂されているのだが・・"
                      , "LINES %man5% なにしろこの広さだから皆目見当がつかないんだ。"
                      , "SPEAK %avatar% レイラ ところで今回の別ミッションだけど"
                      , "SPEAK %avatar% レイラ マルティーニに捕まってここに流されてるある人物を探してほしいの"
                      , "SPEAK %avatar% もじょ 誰を探すのだ？"
                      , "SPEAK %avatar% レイラ レジスタンスの人間なんだけど一度だけ内偵のために霊子力研究所に潜入して捕まったのよ"
                      , "SPEAK %avatar% レイラ 霊子力研究所はマルティーニの力の源泉だし亜人もたくさん捕まってるわ"
                      , "LINES %avatar% その人を探して情報を聞くんだね？"
                      , "SPEAK %avatar% レイラ 霊子力研究所はほんとにガードが固くて全然情報が得られないの。"
                      , "SPEAK %avatar% レイラ 唯一忍び込むのに成功したのが彼だけなのよ"
                      , "SPEAK %avatar% レイラ でも流刑地に流されたというだけで消息不明で・・"
                      , "SPEAK %avatar% もじょ じゃそいつを探すのだ"
                    ]
                }
              , "man5_speak2": {
                    "condition": {"muma_cleared":true}
                  , "trigger": "hero"
                  , "pos": [17,13], "rb": [19,14]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man5%"
                      , "LINES %man5% やあ[NAME]。元気かい？"
                    ]
                }
              , "man6_speak": {
                    "trigger": "hero"
                  , "pos": [9,13], "rb": [11,14]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %man6%"
                      , "LINES %man6% 夢魔って知ってるか？"
                      , "LINES %man6% 人間の良心の呵責に入り込んで憑り殺すんだ"
                      , "LINES %man6% ここには罪人ばっかりだから夢魔にとっては天国だよ"
                      , "LINES %man6% 夜眠れないと訴える奴がいたら気をつけな"
                    ]
                }
              , "old2_speak": {
                    "ignition": {"!has_memory":"worker4_with"}
                  , "trigger": "hero"
                  , "pos": [17,8], "rb": [19,9]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %old2%"
                      , "LINES %old2% わしは闇医者じゃよ・・"
                      , "LINES %old2% 毎日毎日忙しいわい・・ヒャヒャヒャ！"
                      , "LINES %old2% 毎日生体解剖、新薬実験のし放題じゃ。ここはこの世界の医学の最先端といってええ・・"
                      , "LINES %old2% その成果はマルティーニ朝の医学にも役立っておるでな・・ゴミも使いようじゃて。"
                      , "LINES %old2% ヒャヒャヒャ！！"
                    ]
                }
              , "old2_speak2": {
                    "ignition": {"has_memory":"worker4_with"}
                  , "trigger": "hero"
                  , "pos": [17,7], "rb": [19,8]
                  , "type": "lead"
                  , "leads": [
                        "AALGN %avatar% %old2%"
                      , "LINES %old2% わしは闇医者じゃよ・・"
                      , "LINES %old2% 毎日毎日忙しいわい・・ヒャヒャヒャ！"
                      , "LINES %avatar% すいません。この人、具合がわるいらしいんですけど。"
                      , "LINES %old2% ん？どれどれ・・金はもっておるかな？薬は高いぞい。"
                      , "SPEAK %avatar% ワーカー そんな、金なんて持っていません・・ゴホ・・ゴホ・・"
                      , "LINES %old2% それじゃ、治療はあきらめるんじゃな。"
                      , "LINES %avatar% そんな・・なんとかしてあげてください"
                      , "LINES %old2% 金をもってないんじゃろう？ならば新薬実験でもするかな？ならばタダじゃぞ？。"
                      , "SPEAK %avatar% ワーカー い、いやだ。新薬はみんなあっというまに廃人になるじゃないか・・"
                      , "SPEAK %avatar% ワーカー なあ、あんた。たのむ。おれの隠し財産を取ってきてくれないか。"
                      , "SPEAK %avatar% ワーカー 場所はマンホールから地下に入って奥深くだ・・モンスターもうろうろしている・・"
                      , "SPEAK %avatar% もじょ なーんでそんなとこに隠すのだ"
                      , "SPEAK %avatar% もじょ いざって時全く使えないのだ"
                      , "SPEAK %avatar% ワーカー それくらいのところに隠さないとあっという間に盗人に取られてしまうんだ。たのむ・・"
                      , "LINES %avatar% わ、わかりました。"
                      , "SPEAK %avatar% ワーカー ありがとう・・頼んだよ"
                    ]
                  , "chain": "goal"
                }
              , "worker_speak": {
                    "condition": {"cleared":false}
        				  , "trigger": "hero"
                  , "pos": [2,15], "rb": [8,19]
                  , "type": "lead"
                  , "leads": [
                        "LINES %worker1% おーえす！おーえす！"
                      , "LINES %worker2% 働け！働け！"
                      , "SPEAK %avatar% もじょ 工事現場なのだ。波止場の増築してるみたいなのだ。"
                      , "LINES %worker3% ここじゃ体を壊すまで働くんだ！"
                      , "LINES %worker3% 使い物にならなくなったら終わりだぞ！"
                      , "LINES %avatar% た・・大変な作業だなこりゃ・・"
                      , "LINES %worker4% ううう、助けてくれ・・"
                      , "LINES %worker2% おーい！こいつを闇医者につれていけ。たった今オシャカになった。"
                      , "LINES %worker4% ううう、痛い・・"
                      , "LINES %avatar% だ・・大丈夫ですか？"
                      , "LINES %worker4% 頼む・・闇医者まで連れてってくれないか"
                      , "LINES %avatar% わ・・わかりました！"
                      , "LINES %worker4% ありがとう・・。"
                    ]
                  , "chain": "worker4_exit"
                }
              , "worker4_exit": {
                    "type": "unit_exit"
                  , "exit_target": "worker4"
                  , "memory_on": "worker4_with"
                }
              , "worker_speak2": {
                    "condition": {"gesui_cleared":false}
                  , "ignition": {"!has_memory":"worker4_with"}
        				  , "trigger": "hero"
                  , "pos": [2,15], "rb": [8,19]
                  , "type": "lead"
                  , "leads": [
                        "LINES %worker1% おーえす！おーえす！"
                      , "LINES %worker2% 働け！働け！"
                      , "SPEAK %avatar% もじょ 工事現場なのだ。波止場の増築してるみたいなのだ。"
                      , "LINES %worker3% ここじゃ体を壊すまで働くんだ！"
                      , "LINES %worker3% 使い物にならなくなったら終わりだぞ！"
                      , "LINES %avatar% た・・大変な作業だなこりゃ・・"
                    ]
                }
              , "worker_thanks": {
                    "condition": {"gesui_cleared":true}
        				  , "trigger": "hero"
                  , "pos": [2,15], "rb": [8,19]
                  , "type": "lead"
                  , "leads": [
                        "LINES %worker1% おーえす！おーえす！"
                      , "LINES %worker2% 働け！働け！"
                      , "SPEAK %avatar% もじょ 工事現場なのだ。波止場の増築してるみたいなのだ。"
                      , "LINES %worker3% ここじゃ体を壊すまで働くんだ！"
                      , "LINES %worker3% 使い物にならなくなったら終わりだぞ！"
                      , "LINES %avatar% た・・大変な作業だなこりゃ・・"
                      , "LINES %worker4% あ！[NAME]さん・・！"
                      , "LINES %worker4% この間はありがとう。おかげで助かりました"
                    ]
                }
              , "goal": {
                    "type": "escape"
                  , "escape_result": "success"
                }
            }
          , "units": [
                {
                    "character_id": -9906
                  , "code": "man1"
                  , "icon":"man"
                  , "pos": [10,4]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "woman1"
                  , "icon":"woman"
                  , "pos": [18,4]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "man3"
                  , "icon":"man"
                  , "pos": [13,7]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "man4"
                  , "icon":"man"
                  , "pos": [10,9]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "old2"
                  , "icon":"shisyou"
                  , "pos": [18,9]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9907
                  , "code": "man5"
                  , "icon":"man"
                  , "pos": [18,13]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "man6"
                  , "icon":"man"
                  , "pos": [10,13]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "worker1"
                  , "icon":"man"
                  , "pos": [2,18]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "worker2"
                  , "icon":"man"
                  , "pos": [2,19]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "worker3"
                  , "icon":"man"
                  , "pos": [2,18]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
             ,  {
                    "character_id": -9906
                  , "code": "worker4"
                  , "icon":"man"
                  , "pos": [4,17]
                  , "union": 1
                  , "act_brain": "rest"
                  , "brain_noattack": true
                }
            ]
        }
    }
}
