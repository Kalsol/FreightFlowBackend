<?php
// app/Jobs/SendOtpSms.php
namespace App\Jobs;

use App\Services\SMSService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendOtpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $phoneNumber;
    protected string $otp;
    protected string $purpose;

    /**
     * Create a new job instance.
     */
    public function __construct(string $phoneNumber, string $otp, string $purpose)
    {
        $this->phoneNumber = $phoneNumber;
        $this->otp = $otp;
        $this->purpose = $purpose;
    }

    /**
     * Execute the job.
     */
    public function handle(SMSService $smsService): void
    {
      
    }
}