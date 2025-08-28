<?php

class Room_MasterService extends Service {

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたIDのルームを取得する。
     *
     * @param int       ルームID
     * @param reference ついでに敷物情報もほしい場合は、それを格納したい変数を指定する。
     * @return array    一マスを一要素とする2次元配列。parseStructure()の戻り値と同じ。
     */
    public function getRoom($roomId, &$background = null, &$overlayer1 = null, &$overlayer2 = null, &$cover = null, &$mats = null, &$head = null, &$left = null, &$right = null, &$foot = null) {

        // 指定されたルームレコードを取得。
        $room = $this->needRecord($roomId);

        // 敷物情報を取得。
        $mats = $room['mats'] ? json_decode($room['mats'], true) : array();

        // background情報を取得。
        $background = $room['background'] ? $this->parseStructure($room['background']) : array();

        // overlayer1情報を取得。
        $overlayer1 = $room['overlayer1'] ? $this->parseStructure($room['overlayer1']) : array();

        // overlayer2情報を取得。
        $overlayer2 = $room['overlayer2'] ? $this->parseStructure($room['overlayer2']) : array();

        // cover情報を取得。
        $cover = $room['cover'] ? $this->parseStructure($room['cover']) : array();

        // ヘッダー情報を取得。
        $head = $room['structure_head'] ? $this->parseStructure($room['structure_head']) : array();

        // 左情報を取得。
        $left = $room['structure_left'] ? $this->parseStructure($room['structure_left']) : array();

        // 右情報を取得。
        $right = $room['structure_right'] ? $this->parseStructure($room['structure_right']) : array();

        // フッター情報を取得。
        $foot = $room['structure_foot'] ? $this->parseStructure($room['structure_foot']) : array();

        // structure を解析してリターン。
        return $this->parseStructure($room['structure']);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたstructure列を解析して、ルーム構造を返す。
     *
     * @param string    room_master.structure の値
     * @return array    一マスを一要素とする2次元配列。
     *                  第1次元を行番号、第2次元をを列番号、値をマスのIDとしている。
     */
    public function parseStructure($struct) {

        // 空白文字、タブ、\r を除去、また、両端の改行文字も除去。
        $struct = str_replace(array(' ', "\t", "\r"), '', $struct);
        $struct = trim($struct, "\n");

        // 残っている改行文字で区切って配列に。
        $lines = explode("\n", $struct);

        // 戻り値初期化。
        $result = array();

        // 一行ずつ見ていく。
        foreach($lines as $y => $line) {

            $result[$y] = array();

            // 4文字で一マスが構成されているので、切り出していく。
            for($x = 0 ; $x * 4 < strlen($line) ; $x++)
                $result[$y][$x] = (int)substr($line, $x * 4, 4);
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたIDのルームの構造と敷物を保存する。
     *
     * @param int       ルームID
     * @param string    構造をあらわす文字列。
     * @param string    敷物データをJSONエンコードしたもの。
     */
    public function save($roomId, $structure, $background, $overlayer1,$overlayer2, $cover, $mats, $head, $left, $right, $foot) {

        $this->saveRecord(array(
            'room_id' => $roomId,
            'structure' => $structure,
            'background' => $background,
            'overlayer1' => $overlayer1,
            'overlayer2' => $overlayer2,
            'cover' => $cover,
            'mats' => $mats,
            'structure_head' => $head,
            'structure_left' => $left,
            'structure_right' => $right,
            'structure_foot' => $foot
        ));
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'room_id';

    protected $isMaster = true;
}
