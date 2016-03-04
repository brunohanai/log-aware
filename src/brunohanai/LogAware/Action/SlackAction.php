<?php

namespace brunohanai\LogAware\Action;

class SlackAction implements IAction
{
    const NAME = 'log-aware';
    const OPTION_WEBHOOK_KEY = 'webhook_url';
    const OPTION_CHANNEL_KEY = 'channel';
    const OPTION_ICON_KEY = 'icon_emoji';
    const OPTION_USERNAME_KEY = 'username';

    private $curl;
    private $options;

    public function __construct(array $options = array())
    {
        $this->curl = curl_init();
        $this->options = $options;

        curl_setopt($this->curl, CURLOPT_URL, $options[self::OPTION_WEBHOOK_KEY]);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true); // return as string instead output
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($this->curl, CURLOPT_POST, true);
    }

    public function doAction($content)
    {
        $content = array('text' => $content);
        $options = $this->options;

        if (isset($options[self::OPTION_CHANNEL_KEY])) {
            $content[self::OPTION_CHANNEL_KEY] = $options[self::OPTION_CHANNEL_KEY];
        };

        if (isset($options[self::OPTION_ICON_KEY])) {
            $content[self::OPTION_ICON_KEY] = $options[self::OPTION_ICON_KEY];
        };

        if (isset($options[self::OPTION_USERNAME_KEY])) {
            $content[self::OPTION_USERNAME_KEY] = $options[self::OPTION_USERNAME_KEY];
        };

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, array("payload" => json_encode($content)));
        $response = curl_exec($this->curl);

        return json_decode($response);
    }

    public function __destruct()
    {
        curl_close($this->curl);
    }
}