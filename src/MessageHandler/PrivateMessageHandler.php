<?php

namespace App\MessageHandler;

use App\Message\PrivateMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;


#[AsMessageHandler]
class PrivateMessageHandler
{
    public function __invoke(PrivateMessage $message)
    {
        // à completer
    }
}
