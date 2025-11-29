<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OpenAI API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for OpenAI API integration including API key and model
    | settings for embeddings and chat completions.
    |
    */

    'api_key' => env('OPENAI_API_KEY'),

    'organization' => env('OPENAI_ORGANIZATION'),

    /*
    |--------------------------------------------------------------------------
    | Embedding Model
    |--------------------------------------------------------------------------
    |
    | The model to use for generating embeddings. text-embedding-3-small
    | produces 1536-dimensional vectors and is cost-effective.
    |
    */

    'embedding_model' => env('OPENAI_EMBEDDING_MODEL', 'text-embedding-3-small'),

    /*
    |--------------------------------------------------------------------------
    | Embedding Dimensions
    |--------------------------------------------------------------------------
    |
    | The number of dimensions for the embedding vector. This should match
    | the model's output dimensions.
    |
    */

    'embedding_dimensions' => env('OPENAI_EMBEDDING_DIMENSIONS', 1536),

    /*
    |--------------------------------------------------------------------------
    | Chat Completion Model
    |--------------------------------------------------------------------------
    |
    | The model to use for generating chat completions (AI reports).
    |
    */

    'chat_model' => env('OPENAI_CHAT_MODEL', 'gpt-4'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | Timeout in seconds for API requests.
    |
    */

    'timeout' => env('OPENAI_TIMEOUT', 120),

    /*
    |--------------------------------------------------------------------------
    | Max Retries
    |--------------------------------------------------------------------------
    |
    | Maximum number of retries for failed API requests.
    |
    */

    'max_retries' => env('OPENAI_MAX_RETRIES', 3),
];
