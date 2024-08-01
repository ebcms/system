<?php

declare(strict_types=1);

namespace App\Ebcms\System\Http;

use Composer\Autoload\ClassLoader;
use PHP94\Help\Request;
use PHP94\Help\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionClass;

class Api implements RequestHandlerInterface
{
    public function handle(
        ServerRequestInterface $request
    ): ResponseInterface {
        $method = strtolower($request->getMethod());
        if (in_array($method, ['get', 'put', 'post', 'delete', 'head', 'patch', 'options']) && is_callable([$this, $method])) {
            return $this->$method();
        } else {
            return Response::error('不支持该请求');
        }
    }

    public function post()
    {
        $data = Request::post();
        if (!is_array($data)) {
            return Response::error('拒绝');
        }
        $root = dirname((new ReflectionClass(ClassLoader::class))->getFileName(), 3);
        $data = '<?php die();?>' . json_encode($data, JSON_UNESCAPED_UNICODE);
        if (false === file_put_contents($root . '/runtime/~upgrade.php', $data)) {
            return Response::error('文件写入失败！');
        } else {
            return Response::success('接受');
        }
    }
}
