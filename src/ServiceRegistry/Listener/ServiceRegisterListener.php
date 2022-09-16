<?php

declare(strict_types=1);

namespace Imi\Service\ServiceRegistry\Listener;

use Imi\App;
use Imi\Bean\Annotation\Listener;
use Imi\Event\EventParam;
use Imi\Event\IEventListener;
use Imi\Util\Imi;

/**
 * @Listener("IMI.MAIN_SERVER.WORKER.START")
 */
class ServiceRegisterListener implements IEventListener
{
    /**
     * 事件处理方法.
     */
    public function handle(EventParam $e): void
    {
        if (!(
            (Imi::checkAppType('swoole') || Imi::checkAppType('workerman')) // swoole、workerman
        ))
        {
            return;
        }
        /** @var \Imi\Service\ServiceRegistry\ServiceRegistry $serviceRegistry */
        $serviceRegistry = App::getBean('ServiceRegistry');
        $serviceRegistry->register();
    }
}
