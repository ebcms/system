<?php

declare(strict_types=1);

namespace App\Ebcms\System\Http;

use App\Php94\Admin\Http\Common;
use Composer\Autoload\ClassLoader;
use PHP94\Facade\Session;
use PHP94\Help\Response;
use ReflectionClass;
use Throwable;

class Install extends Common
{
    public function get()
    {
        try {
            $systemitem = Session::get('systemitem');
            $root = dirname((new ReflectionClass(ClassLoader::class))->getFileName(), 3);
            $this->upgrade($root . '/upgrade.php');
            if (file_exists($systemitem['tmpfile'])) {
                unlink($systemitem['tmpfile']);
            }
            Session::delete('systemitem');
            return Response::success('升级成功!');
        } catch (Throwable $th) {
            return Response::error($th->getMessage());
        }
    }

    private function upgrade($file)
    {
        if (file_exists($file)) {
            require $file;
            unlink($upgrade_file);
        }
    }
}
