<?php

namespace App\Jobs\Middleware;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;


class RateLimiterForEdis
{
    /**
     * Process the queued job.
     *
     * @param  mixed  $job
     * @param  callable  $next
     * @return mixed
     */
    public function handle($job, $next)
    {
        Redis::throttle('edis')
            ->block(0)->allow(1)->every(5)
            ->then(function () use ($job, $next) {
                Log::info("executed job at ". now());
                $next($job);
            }, function () use ($job) {
                // Could not obtain lock...
                $job->release(5);
            });

    }
}
