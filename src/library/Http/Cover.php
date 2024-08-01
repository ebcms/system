<?php

declare(strict_types=1);

namespace App\Ebcms\System\Http;

use App\Php94\Admin\Http\Common;
use Composer\Autoload\ClassLoader;
use Exception;
use PHP94\Facade\Session;
use PHP94\Help\Response;
use ReflectionClass;
use Throwable;
use ZipArchive;

class Cover extends Common
{
    public function get()
    {
        try {
            $systemitem = Session::get('systemitem');
            $root = dirname((new ReflectionClass(ClassLoader::class))->getFileName(), 3);
            $this->unZip($systemitem['tmpfile'], $root);
            return Response::success('文件更新成功!');
        } catch (Throwable $th) {
            return Response::error($th->getMessage());
        }
    }

    private function unZip($file, $destination)
    {
        $zip = new ZipArchive();
        if ($zip->open($file) !== true) {
            throw new Exception('Could not open archive');
        }
        if (true !== $zip->extractTo($destination)) {
            throw new Exception('Could not extractTo ' . $destination);
        }
        if (true !== $zip->close()) {
            throw new Exception('Could not close archive ' . $file);
        }
    }
}
