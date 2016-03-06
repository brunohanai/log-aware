<?php

namespace brunohanai\LogAware\EventDispatcher;

final class Events
{
    const SYSTEM_START = 'brunohanai.log-aware.system.start';
    const SYSTEM_END = 'brunohanai.log-aware.system.end';

    const WORKER_EXECUTE_FILE_START = 'brunohanai.log-aware.worker.execute_file_start';
    const WORKER_EXECUTE_FILE_END = 'brunohanai.log-aware.worker.execute_file_end';

    const WORKER_FILE_MATCHED = 'brunohanai.log-aware.worker.matched';

    const WORKER_ACTION_DONE = 'brunohanai.log-aware.worker.action_done';
}