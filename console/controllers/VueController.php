<?php

namespace console\controllers;

use yii\console\Controller;

class VueController extends Controller
{
    public function actionMove()
    {
        $templatePath = \Yii::$app->basePath . '/../src/template';
        $newPath = \Yii::$app->basePath . '/../src/ui/src/views';
        $fp           = opendir($templatePath);

        while (false != $file = readdir($fp)) {
            if($file != '.' && $file != '..') {
                $file       = "$file";
                $arr_file[] = $file;
            }
        }

        if(isset($arr_file) && is_array($arr_file)) {
            foreach ($arr_file as $value) {
                $file = explode('.', $value);
                @$new_path = $newPath . '/' . $file[0];
                if(!is_dir($new_path)) {
                    @mkdir($new_path, 0777);
                }
                print_r($value);die;
                if(!file_exists($new_path . $value)) {
                    rename($templatePath . '/' . $value, $new_path . '/' . $value);
                }
            }
//            while (list($key, $value) = each($arr_file)) {
//                $file = explode('.', $value);
//                unset($file[1]);
//                $dir = explode('_', $file[0]);
//                @$new_path = $templatePath . '/' . $dir[1];
//                if(!is_dir($new_path)) {
//                    @mkdir($new_path, 0777);
//                }
//                if(!file_exists($new_path . $value)) {
//                    print_r($templatePath . '/' . $value) . PHP_EOL;
//                    rename($templatePath . '/' . $value, $new_path . '/' . $value);
//                }
//            }
        }
        closedir($fp);
//        print_r($arr_file);
    }
}