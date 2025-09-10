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

class SendOtpSms implements ShouldQueue
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
        try {
            // Call the actual SMS sending logic from the OTPService
            // Or directly from SMSService if OTPService's sending logic is moved there
            $smsService->sendOtpViaApi($this->phoneNumber, $this->otp, $this->purpose);
            Log::info("OTP SMS job processed for {$this->phoneNumber}.");
        } catch (\Throwable $e) {
            Log::error("Failed to send OTP SMS for {$this->phoneNumber} via job: " . $e->getMessage());
            // Handle retry logic or notify administrators if needed
            // $this->release(30); // Release the job back to the queue after 30 seconds
            $this->fail($e); // Mark the job as failed
        }
    }
}