<?php

namespace Di;

#[DefaultPreference(class: ObjectManager::class)]
interface ObjectManagerInterface
{
    /**
     * @param string $type
     * @param array  $arguments
     * @return object
     */
    public function create(string $type, array $arguments = []): object;

    /**
     * @param array $config
     * @return \Di\ObjectManagerInterface
     */
    public function configure(array $config): ObjectManagerInterface;

    /**
     * @param string $type
     * @param array  $arguments
     * @return object
     */
    public function get(string $type, array $arguments = []): object;
}
