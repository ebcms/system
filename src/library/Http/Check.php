<?php

declare(strict_types=1);

namespace App\Ebcms\System\Http;

use App\Ebcms\System\Help\Server;
use App\Php94\Admin\Http\Common;
use PHP94\Help\Response;
use Throwable;

class Check extends Common
{
    public function get()
    {
        try {
            $res = (new Server)->query('/check');
            if ($res['error']) {
                return Response::error($res['message'], $res['redirect_url'] ?? null, $res['data'] ?? null, $res['error'] ?? 1);
            } else {
                return Response::success($res['message'], $res['redirect_url'] ?? null, $res['data'] ?? null);
            }
        } catch (Throwable $th) {
            return Response::error($th->getMessage());
        }
    }
}
