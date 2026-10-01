<?php

namespace Tests\Helpers;

class PokemonApiDataHelper
{
    public static function pikachuResponse(): array
    {
        return [
            'id' => 25,
            'name' => 'pikachu',
            'height' => 4,
            'weight' => 60,
            'types' => [
                [
                    'slot' => 1,
                    'type' => [
                        'name' => 'electric',
                        'url' => 'https://pokeapi.co/api/v2/type/13/',
                    ],
                ],
            ],
            'moves' => [
                [
                    'move' => [
                        'name' => 'thunderbolt',
                        'url' => 'https://pokeapi.co/api/v2/move/85/',
                    ],
                ],
                [
                    'move' => [
                        'name' => 'quick-attack',
                        'url' => 'https://pokeapi.co/api/v2/move/98/',
                    ],
                ],
            ],
            'abilities' => [
                [
                    'ability' => [
                        'name' => 'static',
                        'url' => 'https://pokeapi.co/api/v2/ability/9/',
                    ],
                    'is_hidden' => false,
                ],
                [
                    'ability' => [
                        'name' => 'lightning-rod',
                        'url' => 'https://pokeapi.co/api/v2/ability/31/',
                    ],
                    'is_hidden' => true,
                ],
            ],
        ];
    }

    public static function charizardResponse(): array
    {
        return [
            'id' => 6,
            'name' => 'charizard',
            'height' => 17,
            'weight' => 905,
            'types' => [
                [
                    'slot' => 1,
                    'type' => [
                        'name' => 'fire',
                        'url' => 'https://pokeapi.co/api/v2/type/10/',
                    ],
                ],
                [
                    'slot' => 2,
                    'type' => [
                        'name' => 'flying',
                        'url' => 'https://pokeapi.co/api/v2/type/3/',
                    ],
                ],
            ],
            'moves' => [
                [
                    'move' => [
                        'name' => 'flamethrower',
                        'url' => 'https://pokeapi.co/api/v2/move/53/',
                    ],
                ],
                [
                    'move' => [
                        'name' => 'wing-attack',
                        'url' => 'https://pokeapi.co/api/v2/move/17/',
                    ],
                ],
            ],
            'abilities' => [
                [
                    'ability' => [
                        'name' => 'blaze',
                        'url' => 'https://pokeapi.co/api/v2/ability/66/',
                    ],
                    'is_hidden' => false,
                ],
            ],
        ];
    }

    public static function bulbasaurResponse(): array
    {
        return [
            'id' => 1,
            'name' => 'bulbasaur',
            'height' => 7,
            'weight' => 69,
            'types' => [
                [
                    'slot' => 1,
                    'type' => [
                        'name' => 'grass',
                        'url' => 'https://pokeapi.co/api/v2/type/12/',
                    ],
                ],
                [
                    'slot' => 2,
                    'type' => [
                        'name' => 'poison',
                        'url' => 'https://pokeapi.co/api/v2/type/4/',
                    ],
                ],
            ],
            'moves' => [
                [
                    'move' => [
                        'name' => 'tackle',
                        'url' => 'https://pokeapi.co/api/v2/move/33/',
                    ],
                ],
            ],
            'abilities' => [
                [
                    'ability' => [
                        'name' => 'overgrow',
                        'url' => 'https://pokeapi.co/api/v2/ability/65/',
                    ],
                    'is_hidden' => false,
                ],
            ],
        ];
    }

    public static function notFoundResponse(): array
    {
        return [
            'detail' => 'Not found.',
        ];
    }

    public static function serverErrorResponse(): array
    {
        return [
            'detail' => 'Internal server error.',
        ];
    }

    public static function timeoutResponse(): array
    {
        return [
            'detail' => 'Request timeout.',
        ];
    }
}

