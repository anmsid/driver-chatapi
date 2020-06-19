<?php
/**
 * Created by PhpStorm.
 * User: jjsquady
 * Date: 12/21/18
 * Time: 18:26
 */

namespace ChatApiDriver;

use BotMan\BotMan\Messages\Attachments\Location;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class ChatApiLocationDriver extends ChatApiDriver
{

    const DRIVER_NAME = 'ChatApiLocation';

    /**
     * Determine if the request is for this driver.
     *
     * @return bool
     */
    public function matchesRequest()
    {
        $matches = ! is_null($this->event->get('body')) &&
            ! is_null($this->event->get('chatId')) &&
            ! $this->event->get('fromMe') &&
            ! is_null($this->event->get('type')) &&
            ($this->event->get('type') == 'location');

        return $matches;
    }

    /**
     * Retrieve the chat message(s).
     *
     * @return array
     */
    public function getMessages()
    {

        if (empty($this->messages)) {
            $userId = $this->event->get('chatId');
            $pattern = Location::PATTERN;
            $message = new IncomingMessage($pattern, $userId, $userId, $this->payload);
            list($lat, $long) = explode(';', $this->event->get('body'));
            
            $message->setLocation(new Location($lat, $long));

            
            $this->messages = [$message];
        }
        return $this->messages;
    }

    /**
     * @return bool
     */
    public function isConfigured()
    {
        return false;
    }

}