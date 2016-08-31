<?php

namespace Helper;

trait SpiesOnExceptions
{
    /** @var \Exception */
    private $catchedException;

    /**
     * @param callable $fn
     * @param bool $rethrow
     * @throws \Exception
     */
    public function spyOnException(callable $fn, $rethrow = false)
    {
        try {
            $fn();
        } catch (\Exception $e) {
            $this->catchedException = $e;
            if ($rethrow) {
                throw $e;
            }
        }
    }
}
