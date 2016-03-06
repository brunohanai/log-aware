<?php

namespace brunohanai\LogAware\Action;

use brunohanai\LogAware\Config\Config;

class SlackAction implements IAction
{
    const OPTION_WEBHOOK_KEY = 'webhook_url';
    const OPTION_CHANNEL_KEY = 'channel';
    const OPTION_ICON_KEY = 'icon_emoji';
    const OPTION_USERNAME_KEY = 'username';

    private $name;
    private $curl;
    private $options;

    public function __construct($name, array $options = array())
    {
        $this->name = $name;
        $this->options = $options;
        $this->curl = curl_init();

        curl_setopt($this->curl, CURLOPT_URL, $options[self::OPTION_WEBHOOK_KEY]);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true); // return as string instead output
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_POST, true);
    }

    public function doAction($content)
    {
        $content = array('text' => $content);

        // option, overriding the default channel
        if (isset($this->options[self::OPTION_CHANNEL_KEY])) {
            $content[self::OPTION_CHANNEL_KEY] = $this->options[self::OPTION_CHANNEL_KEY];
        };

        // option, setting icon_emoji
        if (isset($this->options[self::OPTION_ICON_KEY])) {
            $content[self::OPTION_ICON_KEY] = $this->options[self::OPTION_ICON_KEY];
        };

        // option, overriding username
        if (isset($this->options[self::OPTION_USERNAME_KEY])) {
            $content[self::OPTION_USERNAME_KEY] = $this->options[self::OPTION_USERNAME_KEY];
        };

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, array("payload" => json_encode($content)));
        $response = curl_exec($this->curl);

        return json_decode($response);
    }

    public function __destruct()
    {
        curl_close($this->curl);
    }

    public function getType()
    {
        return Config::ACTION_TYPE_SLACK;
    }

    public function getName()
    {
        return $this->name;
    }
}