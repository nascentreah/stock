<?php

return [
    /*
     * Background & color scheme
     */
    'background' => env('BACKGROUND', 'white'),
    'color' => env('COLOR', 'blue'),

    /*
     * Default currency (for storing assets quotes, competitions).
     * When currency is changed scheduled tasks should be run to update asset quotes with respect to the new currency.
     */
    'currency' => env('CURRENCY', 'USD'),

    /*
     * Default market displayed on the markets page
     */
    'market' => env('MARKET', 'NASDAQ'),

    'user_avatar_height' => env('USER_AVATAR_HEIGHT', 300),

    'asset_logo_thumb_height' => env('ASSET_LOGO_THUMB_HEIGHT', 64),

    /*
     * Users configuration
     */
    'users' => [
        // require users to verify their email or not
        'email_verification' => env('USERS_EMAIL_VERIFICATION', FALSE),
    ],

    /*
     * Google reCaptcha
     */
    'recaptcha' => [
        'public_key'    => env('RECAPTCHA_PUBLIC_KEY'),
        'secret_key'    => env('RECAPTCHA_SECRET_KEY'),
    ],

    /*
     * Points settings
     */
    'points_type_trade_loss' => env('POINTS_TYPE_TRADE_LOSS', 2),
    'points_type_trade_profit' => env('POINTS_TYPE_TRADE_PROFIT', 5),
    'points_type_competition_join' => env('POINTS_TYPE_COMPETITION_JOIN', 10),
    'points_type_competition_place1' => env('POINTS_TYPE_COMPETITION_PLACE1', 50),
    'points_type_competition_place2' => env('POINTS_TYPE_COMPETITION_PLACE2', 40),
    'points_type_competition_place3' => env('POINTS_TYPE_COMPETITION_PLACE3', 30),

    'number_decimals' => env('NUMBER_DECIMALS', 2),
    'number_decimal_point' => env('NUMBER_DECIMAL_POINT', 46), // period
    'number_thousands_separator' => env('NUMBER_THOUSANDS_SEPARATOR', 44), // comma

    /*
     * Bots
     */
    'bots' => [
        'top_assets_limit' => env('BOTS_TOP_ASSETS_LIMIT', 100),
        'min_trades_to_open' => env('BOTS_MIN_TRADES_TO_OPEN', 1),
        'max_trades_to_open' => env('BOTS_MAX_TRADES_TO_OPEN', 3),
        'min_trades_to_close' => env('BOTS_MIN_TRADES_TO_CLOSE', 1),
        'max_trades_to_close' => env('BOTS_MAX_TRADES_TO_CLOSE', 3),
        'min_trade_life_time' => env('BOTS_MIN_TRADE_LIFE_TIME', 21600), // min time a trade should be kept open until it's closed
    ],

    /*
     * Google Tag Manager container ID
     */
    'gtm_container_id' => env('GTM_CONTAINER_ID'),


    /*
     * AdSense settings
     */
    'adsense_client_id' => env('ADSENSE_CLIENT_ID'),
    'adsense_top_slot_id' => env('ADSENSE_TOP_SLOT_ID'),
    'adsense_bottom_slot_id' => env('ADSENSE_BOTTOM_SLOT_ID'),

    /*
     * Assets market data feed settings
     */
    'api_assets_chunk_size' => env('API_ASSETS_CHUNK_SIZE', 400),

    /*
     * Competitions settings
     */
    // frequency of requesting quotes updates on competition page
    'competitions_assets_quotes_update_freq' => env('COMPETITIONS_ASSETS_QUOTES_UPDATE_FREQ', 10), // in seconds

    /*
     * openexchangerates.org API key
     */
    'openexchangerates_api_key' => env('OPENEXCHANGERATES_API_KEY'),
];