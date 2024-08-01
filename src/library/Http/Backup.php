<?php

declare(strict_types=1);

namespace App\Ebcms\System\Http;

use App\Ebcms\System\Help\Zip;
use App\Php94\Admin\Http\Common;
use Composer\Autoload\ClassLoader;
use PHP94\Facade\Session;
use PHP94\Help\Response;
use ReflectionClass;
use Throwable;

class Backup extends Common
{
    public function get()
    {
        try {
            $root = dirname((new ReflectionClass(ClassLoader::class))->getFileName(), 3);
            $systemitem = Session::get('systemitem');
            $systemitem['backup_file'] = $root . '/backup/system_' . date('YmdHis') . '.zip';

            $zip = new Zip;
            $zip->open($systemitem['backup_file'], Zip::CREATE);
            $zip->addDirectory($root . '/vendor', $root . '/');
            $zip->addFile($root . '/composer.json', 'composer.json');
            $zip->addFile($root . '/composer.lock', 'composer.lock');
            $zip->close();

            Session::set('systemitem', $systemitem);
            return Response::success('备份成功！', null, $systemitem);
        } catch (Throwable $th) {
            return Response::error($th->getMessage());
        }
    }
}
