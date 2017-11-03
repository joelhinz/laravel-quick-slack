<?php

return [
    /**
     * Set names for your webhooks.
     */
    'webhooks' => [
        'my-webhook' => 'some-webhook-url',
    ],
    
    /**
     * Set a default webhook to use if no other url is given explicitly.
     * This can be either a webhook url, or the name of a named webhook above.
     */
    'default' => '',
];
