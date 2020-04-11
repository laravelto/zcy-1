<?php
/**
 * Arr.php
 *
 * Part of AllinpaySDK.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    Fackeronline <1077341744@qq.com>
 * @link      https://github.com/Fakeronline
 */
namespace Fakeronline\AllinpaySDK\Utils;

class Arr{

    /**
     * 获取数组的值 -- 可深度获取
     * $array = ['names' => ['joe' => ['programmer']]];
     * $value = array_get($array, 'names.joe');
     * @param $array
     * @param $key
     * @param null $default
     * @return null
     */
    public static function get(array $array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }

        if (isset($array[$key])) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }

        return $array;
    }

    public static function getAll(array $array, array $key_array, $filterNull = false){

        $result = [];

        foreach($key_array as $key){

            $temp = self::get($array, $key);

            if(is_null($temp) && $filterNull){
                continue;
            }

            $result[$key] = $temp;

        }

        return $result;

    }

    public static function dot($array, $prepend = '')
    {
        $results = [];
        foreach ($array as $key => $value)
        {
            if (is_array($value))
            {
                $results = array_merge($results, self::dot($value, $prepend.$key.'.'));
            }
            else
            {
                $results[$prepend.$key] = $value;
            }
        }
        return $results;
    }

    /**
     * 将数组里的某一个键值作为数组的索引并返回
     * @param array $array
     * @param $key
     * @return array
     */
    public static function key_advance(array $array ,$key){
        $result = array();
        foreach($array as $item){
            $field = self::get($item, $key);
            if(is_null($field)){
                $result[] = $item;
            }else{
                $result[$field] = $item;
            }
        }
        return $result;
    }

}