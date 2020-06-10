<?php
/**
 * Created by PhpStorm.
 * User: mamtree-mac-002
 * Date: 2018/11/10
 * Time: 4:23 PM
 */

namespace App\Libaries;

use Illuminate\Support\Facades\Facade;
use Illuminate\Validation\Factory;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;

class Validator extends Facade
{
    public static function  getInstance() {
        static $validator = null;
        if ($validator == null) {
            $translation_file_loader = new FileLoader(new \Illuminate\Filesystem\Filesystem,EASYSWOOLE_ROOT . '/Lang');
            $translator = new Translator($translation_file_loader,config('LOCALE'));
            $validator = new Factory($translator);
        }
        return $validator;
    }
}