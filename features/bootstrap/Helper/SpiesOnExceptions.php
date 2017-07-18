<?php

namespace Helper;

trait SpiesOnExceptions
{
    /** @var \Exception */
    private $catchedException;

    /**
     * @param callable $fn
     * @param array $params
     * @param bool $rethrow
     * @throws \Exception
     */
    public function spyOnException(callable $fn, array $params = [], $rethrow = false)
    {
        try {
            call_user_func_array($fn, $params);
        } catch (\Exception $e) {
            $this->catchedException = $e;
            if ($rethrow) {
                throw $e;
            }
        }
    }
}
