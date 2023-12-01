<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Task;

interface TaskInterface
{
    /**
     * Execute task.
     *
     * @param mixed[] $data
     *
     * @return void
     * @throws TaskException
     */
    public function execute(array $data): void;
}
