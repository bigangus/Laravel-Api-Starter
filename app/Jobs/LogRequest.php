<?php

namespace App\Jobs;

use App\Models\System\RequestLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LogRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $parameters;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    public function handle(): void
    {
        RequestLog::create($this->parameters);
    }
}
