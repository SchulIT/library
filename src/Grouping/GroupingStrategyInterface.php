<?php

namespace App\Grouping;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.grouping_strategy')]
interface GroupingStrategyInterface {

    /**
     * @param mixed $object
     * @param array $options
     * @return mixed
     */
    public function computeKey(mixed $object, array $options = [ ]): mixed;

    /**
     * @param mixed $keyA
     * @param mixed $keyB
     * @param array $options
     * @return bool
     */
    public function areEqualKeys(mixed $keyA, mixed $keyB, array $options = [ ]): bool;

    /**
     * @param mixed $key
     * @param array $options
     * @return GroupInterface
     */
    public function createGroup(mixed $key, array $options = [ ]): GroupInterface;
}