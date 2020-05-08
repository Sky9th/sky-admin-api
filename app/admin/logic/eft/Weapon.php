<?php
namespace app\admin\logic\eft;

use app\common\model\eft\Catalogue;
use app\common\model\eft\Combine;
use app\common\model\eft\Relate;
use think\facade\Db;

class Weapon {

    private $weapon;

    private $attach_position = [
        ['muzzle','406,407,408'],
        //['muzzle','407'],
        ['cylinder','738'],
        ['casing','741'],
        ['handle','1064'],
        ['butt','1065'],
        ['grip','739'],
    ];

    public function __construct($weapon_id)
    {
        $weapon = Catalogue::where('id', $weapon_id)->find();
        $this->weapon = $weapon;
    }


    /**
     * 计算组合并录入
     * @param $start
     * @param $series
     * @return array
     */
    public function create ($start, $series, $limit) {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
        $attaches = $this->attach();
        $combines = $this->combine($attaches, $start, $limit);

        $prefix = 'client_eft_combine_';
        foreach ($combines as $combine) {
            $data = $this->calculate($this->weapon, $combine, $series);
            $res = Db::table($prefix. $this->weapon->id)->save($data);
            if (!$res) {
                return error();
            }
        }
        return success();
    }

    /**
     * 计算组合总数
     * @return int
     * @throws
     */
    public function count () {
        $attaches = $this->attach();
        $len = 1;
        $recIndex = null; //记录当前该取的位置
        $count_3 = 0;
        foreach ($attaches as $key => $value) {
            $lenRec[$count_3] = count($value);
            $len = $lenRec[$count_3]*$len;
            $recIndex[] = 0;//第一次全部取第0个
            $count_3++;
        }
        return $len;
    }

    /**
     * 获取附件
     * @param $attaches
     * @return array
     * @throws
     */
    public function attach () {
        $attaches = [];
        foreach ($this->attach_position as $key => $value) {
            $attach = Db::table(Relate::getTable())->where('a.main_id', $this->weapon->id)->where('b.pid', 'in', $value[1])->alias('a')->join(Catalogue::getTable().' b','a.relate_id = b.id')->select();
            $ids = [];
            foreach ($attach as $k => $item) {
                $this->extend($item, $item['id']/*.'['.$attach['title'].']'*/, $ids);
            }
            $attaches[] = $ids;
        }
        var_dump($attaches);
        return $attaches;
    }

    /**
     * 计算武器属性
     * @param $weapon
     * @param $attach
     * @param $series
     * @return array
     * @throws
     */
    public function calculate ($weapon, $attach, $series) {
        $info = $weapon->toArray();
        $recoil_percent = 0;
        $effect_change = 0;
        $code = [];
        foreach ($attach as $key => $value) {
            $code = array_merge($code, explode(',', $value));
            if (!is_string($value)) {
                $value .= '';
            }
            $list = Catalogue::where('id','in', $value)->select();
            foreach ($list as $k => $v) {
                $recoil_percent += $v['recoil'];
                $effect_change += $v['effect'];
            }
        }
        $x_recoil = $info['x_recoil'] * ((100 - $recoil_percent) / 100);
        $y_recoil = $info['y_recoil'] * ((100 - $recoil_percent) / 100);
        $effect = $info['effect'] + $effect_change;
        $data = [
            'series' => $series,
            'code' => implode('-', $code),
            'moa' => 0,
            'muzzle' => $attach['muzzle'],
            'cylinder' => $attach['cylinder'],
            'casing' => $attach['casing'],
            'handle' => $attach['handle'],
            'butt' => $attach['butt'],
            'grip' => $attach['grip'],
            'decrease_recoil' => $recoil_percent,
            'effect_change' => $effect_change,
            'x_recoil' => $x_recoil,
            'y_recoil' => $y_recoil,
            'effect' => $effect
        ];
        return $data;
    }

    /**
     * 附件组合计算
     * @param $attach
     * @param $pre
     * @param $ids
     * @throws
     */
    public function extend ($attach, $pre, &$ids) {
        $main_id = $attach['id'];
        $where = [];
        switch ($attach['pid']) {
            case 362: //枪口

                break;
            case 738:  //导气箍
                $where[] = ['b.pid','=','740'];
                break;
            case 740:  //护木
                $jz = [1127,1153,1177];
                $front_handle = Catalogue::where('pid', 361)->column('id');
                $where[] = ['b.id','in', array_merge($jz, $front_handle)];
                break;
            case 1062: //基座
                $where[] = ['b.pid','in','361'];
                break;
        }
        $extend = Db::table(Relate::getTable())->field('b.*')->where('a.main_id', $main_id)->where($where)->alias('a')->join(Catalogue::getTable().' b','a.relate_id = b.id')->select();
        if (count($extend) > 0) {
            foreach ($extend as $key=>$item) {
                $name = $pre. ',' . $item['id']/*.'['.$item['title'].']'*/;
                $this->extend($item, $name, $ids);
            }
        } else{
            $ids[] = $pre;
        }
    }

    /**
     * 多数组组合计算
     * @param $series
     * @param $attaches
     * @param $start
     * @param $limit
     * @return int|array
     */
    public function combine ($attaches, $start = 1, $limit = 100000) {
        $len = 1;
        $arrLen = count($attaches); //需要排列数组有多少个
        $recIndex = null; //记录当前该取的位置
        //foreach 计数
        $count_3 = 0;
        foreach ($attaches as $key => $value) {
            $lenRec[$count_3] = count($value);
            $len = $lenRec[$count_3]*$len;
            $recIndex[] = 0;//第一次全部取第0个
            $count_3++;
        }
        //算出% 的值
        $count = 1;
        foreach ($lenRec as $key => $value) {
            $moduloVal = 1;

            if($arrLen == $count){
                $modulo[] = count($attaches[$arrLen-1]); //等于最后一个的长度
            }else{
                $count_1 = 1;
                foreach ($lenRec as $index => $item) {
                    $count_1 > $count && $moduloVal = $moduloVal*$item;
                    $count_1 ++;
                }
                $modulo[] = $moduloVal;
            }
            $count ++;//为了防止$d key是有值的 不是自然序列 需要计数
        }
        $i = $start;

        $combines = [];
        while ($i <= $len && $i < $start + $limit) {
            $temp = [];
            $count_2 = 0;// 取模
            foreach ($attaches as $key=>$value) {
                $temp[$this->attach_position[$key][0]] = $value[$recIndex[$count_2]%$lenRec[$count_2]];
                $count_2 ++;
            }

            $combines[] = $temp;
            foreach ($modulo as $key => $value) {
                if($i%$value == 0 && $key < $arrLen - 1 ){
                    $recIndex[$key] = $recIndex[$key] +1;
                }
                if($key == $arrLen - 1){
                    if($i%$value == 0){
                        $recIndex[$key] = 0;
                    }else{
                        $recIndex[$key] = $recIndex[$key] +1;
                    }
                }
            }
            $i ++;
            //改变获取的位置
        }

        return $combines;
    }

}