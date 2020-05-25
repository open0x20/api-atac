<?php

namespace App\Helper;


use Symfony\Component\Console\Command\LockableTrait;

class LockHelper
{
    use LockableTrait;

    /**
     * Returns true if the lock was aquired.
     * Returns false if a lock already exists.
     * @return bool
     */
    public function getLock()
    {
        if (!$this->lock()) {
            return false;
        }

        return true;
    }

    public function releaseLock()
    {
        $this->release();
    }

    public function getName()
    {
        return 'app:worker';
    }
}
