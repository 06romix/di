<?php
declare(strict_types=1);

namespace Di;

class ObjectManager implements ObjectManagerInterface
{
    private array $sharedInstances;
    private array $preference = [];

    /**
     * @param array $sharedInstances
     */
    public function __construct(&$sharedInstances = [])
    {
        $this->sharedInstances = &$sharedInstances;
        $this->sharedInstances[__CLASS__] = $this;
    }

    public function create(string $type, array $arguments = []): object
    {
        if (isset($this->preference[$type])) {
            $type = $this->preference[$type];
        }

        $reflection = new \ReflectionClass($type);

        foreach ($reflection->getAttributes(DefaultPreference::class) as $attribute) {
            $type = $attribute->getArguments()['class'];
        }

        $constructor = $reflection->getConstructor();
        $params = [];
        if ($constructor) {
            foreach ($constructor->getParameters() as $parameter) {
                $params[$parameter->getName()] = [
                    'class' => $parameter->getType() ? $parameter->getType()->getName() : null,
                    'value' => $parameter->isOptional() ? $parameter->getDefaultValue() : false,
                ];
            }

            $params = $this->resolveArguments($params, $arguments);
        }

        return new $type(...$params);
    }

    public function configure(array $config): ObjectManagerInterface
    {
        $this->preference = $config['preference'];
        return $this;
    }

    public function get(string $type, array $arguments = []): object
    {
        if (! isset($this->sharedInstances[$type])) {
            $this->sharedInstances[$type] = $this->create($type, $arguments);
        }

        return $this->sharedInstances[$type];
    }

    /**
     * @param array $params
     * @param array $arguments
     * @return array
     */
    private function resolveArguments(array $params, array $arguments) : array
    {
        $result = [];
        foreach ($params as $key => $param) {
            if (isset($arguments[$key])) {
                $param['value'] = $arguments[$key];
            }

            if ($param['class']) {
                $result[] = $this->get($param['class']);
            } else {
                $result[] = $param['value'];
            }
        }

        return $result;
    }
}
