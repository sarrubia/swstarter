<?php

namespace App\Services\SwApi\Decorators;

use App\Lib\Utils\ArrayUtils;

class PersonDecorator {

    public const FIELD_UID = 'uid';

    public const FIELD_NAME = 'name';

    public const FIELD_LINK = 'link';

    public const UNKNOWN = 'unknown';

    public static function getLink(array $person): array {

        return [
            self::FIELD_UID => ArrayUtils::keyExists(self::FIELD_UID, $person, self::UNKNOWN ),
            self::FIELD_NAME => ArrayUtils::keyExists(self::FIELD_NAME, $person, self::UNKNOWN ),
            self::FIELD_LINK => '/api/people/' . ArrayUtils::keyExists(self::FIELD_UID, $person, self::UNKNOWN ),
        ];

    }
}
