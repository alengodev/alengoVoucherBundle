<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Helper;

use Ramsey\Uuid\Uuid;

class DataModifier
{
    public function modifyData($data)
    {
        if (\is_iterable($data)) {
            $values = [];

            foreach ($data as $key => $value) {
                $values[$key] = $value;
                if (!\array_key_exists('uuid', $value)) {
                    $values[$key]['uuid'] = Uuid::uuid4()->toString();
                }
            }

            return $values;
        }

        return $data;
    }
}
