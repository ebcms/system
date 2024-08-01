<?php

declare(strict_types=1);

namespace App\Ebcms\System\Http;

use App\Ebcms\System\Help\Curl;
use App\Php94\Admin\Http\Common;
use PHP94\Facade\Session;
use PHP94\Help\Response;
use Throwable;

class Download extends Common
{

    public function get()
    {
        try {
            $systemitem = Session::get('systemitem');
            if (false === $content = Curl::get($systemitem['source'])) {
                return Response::error('升级包下载失败，请稍后再试~');
            }

            if (md5($content) != $systemitem['md5']) {
                return Response::error('校验失败！');
            }

            $tmpfile = tempnam(sys_get_temp_dir(), 'systemitem');

            if (false === file_put_contents($tmpfile, $content)) {
                return Response::error('文件' . $tmpfile . '写入失败，请检查权限~');
            }
            $systemitem['tmpfile'] = $tmpfile;
            Session::set('systemitem', $systemitem);

            return Response::success('下载成功！');
        } catch (Throwable $th) {
            return Response::error($th->getMessage());
        }
    }
}
