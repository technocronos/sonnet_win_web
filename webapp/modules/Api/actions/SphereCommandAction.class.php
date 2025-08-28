<?php

/**
 * フィールドクエストで、ユーザのコマンドを受け付けるアクション。
 */
class SphereCommandAction extends SmfBaseAction {

    protected function doExecute($params) {

        // 指定されたスフィアの情報をロード。
        $record = Service::create('Sphere_Info')->needRecord($params['id']);

        // 他人のスフィアならエラー。
        if($record['user_id'] != $this->user_id)
            throw new MojaviException('他人のスフィアでコマンドを実行しようとした');

        // バリデーションコードが合わないならエラー。
        if($record['validation_code'] != $params['code'])
            throw new MojaviException('バリデーションコードが合わない');

        // スフィアを制御するオブジェクトを作成。
        $sphere = SphereCommon::load($record);

        // コマンドを処理して、指揮内容を受け取る。
        $get = Common::cutRefArray($params);
        $leads = $sphere->command($get);

        // 指揮内容 0 だとFLASHで処理できないので、NOOPにする。
        if(count($leads) == 0)
            $leads[] = 'NOOP';

        // 指揮内容をレスポンスに変換。
        $array = [];
  	    $array['result'] = "ok";
  	    $array['leadNum'] = count($leads);
  	    for($i = 0 ; $i < count($leads) ; $i++)
  	        $response['lead' . ($i+1)] = $leads[$i];

  	    $array['lead'] = $response;

        // テスト環境の場合はログに残す。
        if(ENVIRONMENT_TYPE == 'test') {

            ob_start();
            var_dump($response);
            $output = ob_get_contents();
            ob_end_clean();

            $output = str_repeat('-', 80) . "\n" . $output;
            file_put_contents(MO_LOG_DIR.'/sphere_command.log', $output, FILE_APPEND);
        }

        // レスポンス内容をリターン。
        return $array;
    }
}
