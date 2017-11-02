<?php

namespace JoelHinz\LaravelQuickSlack;

use Exception;
use GuzzleHttp\Client;

class LaravelQuickSlack
{
    /**
     * The channel to post to, either a url or the name of a channel in the config.
     *
     * @var string
     */
    private $channel;

    /**
     * Messages variables that can be remembered.
     *
     * @var array
     */
    private $memory = ['channel'];

    /**
     * Set the channel as named in the config to post to.
     * Optionally remember the name for next call.
     *
     * @param  strung $channel
     * @param  bool $remember
     * @return self
     */
    public function to($channel, $remember = false)
    {
        $this->channel = $channel;

        $this->memory['channel'] = $remember;

        return $this;
    }

    /**
     * Send a message to a Slack channel.
     *
     * @param  string $message
     * @return self
     */
    public function send($message)
    {
        (new Client)->post($this->getEndpoint(), [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($message),
        ]);

        return $this->reset();
    }

    /**
     * Forget message data that should not be remembered.
     *
     * @return self
     */
    public function reset()
    {
        foreach ($this->memory as $key => $remember) {
            if (!$remember) {
                $this->$key = null;
            }
        }

        return $this;
    }

    /**
     * Figure out which endpoint to use and format it if needed.
     *
     * @return string
     */
    private function getEndpoint()
    {
        $endpoint = $this->chooseEndpoint();

        if (!starts_with($endpoint, 'http')) {
            $endpoint .= 'https://hooks.slack.com/services/';
        }

        return $endpoint;
    }

    /**
     * Figure out which endpoint to use. In descending priority:
     * 1. $this->to() has been used to set a url
     * 2. $this->to() has been used to set a channel name available in config
     * 3. config default channel is a url
     * 4. config default channel is a channel name available in config
     *
     * @return string
     */
    private function chooseEndpoint()
    {
        if (filter_var($this->channel, FILTER_VALIDATE_URL)) {
            return $this->channel;
        }

        if (!is_null($this->channel)) {
            return config('quick-slack'.$this->channel);
        }

        if (filter_var(config('quick-slack.default'), FILTER_VALIDATE_URL)) {
            return config('quick-slack.default');
        }

        return config('quick-slack.'.config('quick-slack.default'));
    }
}
