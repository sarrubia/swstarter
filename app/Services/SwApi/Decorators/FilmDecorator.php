<?php

namespace App\Services\SwApi\Decorators;

use App\Lib\Utils\ArrayUtils;

class FilmDecorator {
    public const FIELD_UID = 'uid';

    public const FIELD_TITLE = 'title';

    public const FIELD_LINK = 'link';

    public const UNKNOWN = 'unknown';
    public static function getFilmLink(array $film): array {

        return [
            self::FIELD_UID => ArrayUtils::keyExists(self::FIELD_UID, $film, self::UNKNOWN ),
            self::FIELD_TITLE => ArrayUtils::keyExists(self::FIELD_TITLE, $film, self::UNKNOWN ),
            self::FIELD_LINK => '/api/films/' . ArrayUtils::keyExists(self::FIELD_UID, $film, self::UNKNOWN ),
        ];

    }
}
