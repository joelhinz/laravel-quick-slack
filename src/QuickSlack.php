<?php

namespace JoelHinz\LaravelQuickSlack;

use GuzzleHttp\Client;

class QuickSlack
{
    /**
     * The webhook to post to, either a url or the name of a webhook in the config.
     *
     * @var string
     */
    private $webhook;

    /**
     * Messages variables that can be remembered.
     *
     * @var array
     */
    private $memory = [
        'webhook' => false,
    ];

    /**
     * Send a message, then reset and return self.
     *
     * @param  string $message
     * @return self
     */
    public function send($message)
    {
        $this->post($message);

        $this->reset();

        return $this;
    }

    /**
     * Post a message to a Slack webhook.
     *
     * @param  string $message
     * @return void
     */
    private function post($message)
    {
        (new Client)->post($this->getWebhook(), [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode(['text' => $message]),
        ]);
    }

    /**
     * Set the webhook as named in the config to post to.
     * Optionally remember the name for next call.
     *
     * @param  strung $webhook
     * @param  bool|null $remember
     * @return self
     */
    public function to($webhook, $remember = null)
    {
        $this->webhook = $webhook;

        if (!is_null($remember)) {
            $this->memory['webhook'] = (bool) $remember;
        }

        return $this;
    }

    /**
     * Forget the remembered webhook, if any.
     *
     * @return self
     */
    public function forgetRecipient()
    {
        $this->forget('webhook');

        return $this;
    }

    /**
     * Forget a remembered message data variable.
     *
     * @param  string $type
     * @return void
     */
    private function forget($type)
    {
        if (isset($this->$type)) {
            $this->$type = null;
        }

        if (isset($this->memory[$type])) {
            $this->memory[$type] = false;
        }
    }

    /**
     * Forget message data that should not be remembered.
     *
     * @return void
     */
    private function reset()
    {
        foreach ($this->memory as $key => $remember) {
            if ($remember === false) {
                $this->$key = null;
            }
        }
    }

    /**
     * Figure out which webhook to use, look it up in the config file,
     * and format it if needed.
     *
     * @return string
     */
    private function getWebhook()
    {
        return (new WebhookTransformer($this->webhook))->transform();
    }
}
