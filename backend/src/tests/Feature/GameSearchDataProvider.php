<?php

namespace Tests\Feature;

use App\Enums\GameSearchSort;

class GameSearchDataProvider
{
    public static function gamesForStringSearchProvider(): array
    {
        return [
            // 1st set
            'finds one - matching description' => [
                'adventure',
                [
                    ['name' => 'The Great Escape'],
                    ['description' => 'A desert adventure'],
                    ['name' => 'Space Odyssey'],
                ],
                [],
                [
                    ['data.0.description' => 'A desert adventure']
                ]
            ],
            // 2nd set
            'finds three - matching name, description, creator name' => [
                'my',
                [
                    ['name' => 'My big adventure'],
                    ['description' => 'My game'],
                    ['name' => 'The ABC savers'],
                ],
                [
                    ['name' => 'John'],
                    ['name' => 'Richard'],
                    ['name' => 'Mylock'],
                ],
                [
                    ['data.0.name' => 'My big adventure'],
                    ['data.1.description' => 'My game'],
                    ['data.2.creator.name' => 'Mylock'],
                ]
            ],
            // 3rd set
            'finds nothing' => [
                'cool game',
                [
                    ['name' => 'The greatest game ever', 'description' => 'This game is very great.'],
                    ['name' => 'c00l g4m3', 'description' => 'My game is sooo c00l :)'],
                    ['name' => 'Super lame adventure', 'description' => 'Basically nothing happens'],
                ],
                [
                    ['name' => 'A great guy'],
                    ['name' => 'C00l Guy'],
                    ['name' => 'Whocares123'],
                ],
                []
            ]
        ];
    }

    public static function gamesForOrderProvider(): array
    {
        return [
            // 1st set
            'name asc' => [
                GameSearchSort::NAME->value,
                true,
                [
                    ['name' => 'Beta Game'],
                    ['name' => 'Zeta Game'],
                    ['name' => 'Alpha Game'],
                ],
                [],
                [
                    ['data.0.name' => 'Alpha Game'],
                    ['data.2.name' => 'Zeta Game'],
                ]
            ],
            // 2nd set
            'name desc' => [
                GameSearchSort::NAME->value,
                false,
                [
                    ['name' => 'Beta Game'],
                    ['name' => 'Zeta Game'],
                    ['name' => 'Alpha Game'],
                ],
                [],
                [
                    ['data.0.name' => 'Zeta Game'],
                    ['data.2.name' => 'Alpha Game'],
                ]
            ],
            // 3rd set
            'created asc' => [
                GameSearchSort::CREATED_AT->value,
                true,
                [
                    ['created_at' => now(), 'name' => 'Game 1'],
                    ['created_at' => now()->subDay(), 'name' => 'Game 2'],
                    ['created_at' => now()->subMonth(), 'name' => 'Game 3'],
                ],
                [],
                [
                    ['data.0.name' => 'Game 3'],
                    ['data.2.name' => 'Game 1'],
                ]
            ],
            // 4th set
            'created desc' => [
                GameSearchSort::CREATED_AT->value,
                false,
                [
                    ['created_at' => now()->subWeek(), 'name' => 'Game 1'],
                    ['created_at' => now()->addMonth(), 'name' => 'Game 2'],
                    ['created_at' => now()->now(), 'name' => 'Game 3'],
                ],
                [],
                [
                    ['data.0.name' => 'Game 2'],
                    ['data.2.name' => 'Game 1'],
                ]
            ],
            // 5th set
            'modified asc' => [
                GameSearchSort::LAST_MODIFIED->value,
                true,
                [
                    ['last_modified' => now()->addMinute(), 'name' => 'Game 1'],
                    ['last_modified' => now()->subMinutes(10), 'name' => 'Game 2'],
                    ['last_modified' => now()->addMinutes(10), 'name' => 'Game 3'],
                ],
                [],
                [
                    ['data.0.name' => 'Game 2'],
                    ['data.2.name' => 'Game 3'],
                ]
            ],
            // 6th set
            'modified desc' => [
                GameSearchSort::LAST_MODIFIED->value,
                false,
                [
                    ['last_modified' => now()->addWeeks(2), 'name' => 'Game 1'],
                    ['last_modified' => now()->addWeeks(12), 'name' => 'Game 2'],
                    ['last_modified' => now()->addWeeks(20), 'name' => 'Game 3'],
                ],
                [],
                [
                    ['data.0.name' => 'Game 3'],
                    ['data.2.name' => 'Game 1'],
                ]
            ],
            // 7th set
            'creator asc' => [
                GameSearchSort::CREATOR_NAME->value,
                true,
                [
                    ['name' => 'First'],
                    ['name' => 'Second'],
                    ['name' => 'Third'],
                ],
                [
                    ['name' => 'Alpha player'],
                    ['name' => 'Zeta player'],
                    ['name' => 'Beta player'],
                ],
                [
                    ['data.0.name' => 'First'],
                    ['data.1.name' => 'Third'],
                    ['data.2.name' => 'Second'],
                ]
            ],
            // 8th set
            'creator desc' => [
                GameSearchSort::CREATOR_NAME->value,
                false,
                [
                    ['name' => 'First'],
                    ['name' => 'Second'],
                    ['name' => 'Third'],
                ],
                [
                    ['name' => 'Alpha player'],
                    ['name' => 'Zeta player'],
                    ['name' => 'Beta player'],
                ],
                [
                    ['data.0.name' => 'Second'],
                    ['data.1.name' => 'Third'],
                    ['data.2.name' => 'First'],
                ]
            ],
        ];
    }
}
