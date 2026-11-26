<?php

return [
    // Public portfolio deployment: deny all state-changing web requests.
    // Defaults to false, preserving the original local-development behaviour.
    'read_only' => env('OPUS_READ_ONLY', false),
];
