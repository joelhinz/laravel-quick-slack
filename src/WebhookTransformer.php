<?php

namespace JoelHinz\LaravelQuickSlack;

class WebhookTransformer
{
    /**
     * The webhook being handled.
     *
     * @var string
     */
    private $webhook;

    public function __construct($webhook)
    {
        $this->webhook = $webhook ?: $this->getDefault();
    }

    /**
     * Pass the webhook through a series of transformations
     * in order to find the correct one and format it accordingly.
     *
     * @return string
     */
    public function transform()
    {
        $this->recursivelyLoadConfig();

        $this->insertWebhookPrefix();

        return $this->webhook;
    }

    /**
     * Load the default webhook from the configuration.
     *
     * @return void
     */
    private function getDefault()
    {
        return config('quick-slack.default');
    }

    /**
     * Recursively look for a webhook matching the current name
     * in the configuration file.
     *
     * @return void
     */
    private function recursivelyLoadConfig()
    {
        $fromConfig = config('quick-slack.webhooks.'.$this->webhook);

        if (is_null($fromConfig)) {
            return;
        }

        $this->webhook = $fromConfig;

        $this->recursivelyLoadConfig();
    }

    /**
     * Prefix the webhook with Slack's base url if it's not a valid url already.
     *
     * @return void
     */
    private function insertWebhookPrefix()
    {
        if (!filter_var($this->webhook, FILTER_VALIDATE_URL)) {
            $this->webhook = 'https://hooks.slack.com/services/'.ltrim($this->webhook, '/');
        }
    }
}
