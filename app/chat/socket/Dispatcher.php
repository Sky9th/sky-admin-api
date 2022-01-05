<?php

declare(strict_types=1);

namespace app\chat\socket;

use think\helper\Str;
use think\swoole\websocket\Event;

class Dispatcher
{

    /**
     * 事件监听处理
     * @param Event $event
     */
    public function handle($event)
    {
        // 自行触发事件
        // 为了防止事件名冲突，添加 swoole.websocket.Event. 前缀
        var_dump('swoole.websocket.Event.' . Str::studly($event->type));
        event('swoole.websocket.Event.' . Str::studly($event->type),  $event->data);
    }
}