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
            $uuid = [];

            foreach ($data as $key => $value) {
                $values[$key] = $value;

                if (!\array_key_exists('uuid', $value) || \in_array($value['uuid'], $uuid, true)) {
                    $values[$key]['uuid'] = Uuid::uuid4()->toString();
                }

                $uuid[] = $values[$key]['uuid'];
            }

            return $values;
        }

        return $data;
    }
}
