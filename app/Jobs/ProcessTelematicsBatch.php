<?php

namespace App\Jobs;

use App\Domains\Logistics\Models\Telematics;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessTelematicsBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param array<int, array<string, mixed>> $batch
     */
    public function __construct(private readonly array $batch)
    {
    }

    public function handle(): void
    {
        foreach ($this->batch as $payload) {
            Telematics::create($payload);
        }
    }
}


