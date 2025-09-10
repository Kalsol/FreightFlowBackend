# This PowerShell script creates the complete file and directory structure
# for the FreightFlow Laravel application based on a domain-driven architecture.

# Use a variable for the base path for easy modification
$basePath = ".\app"

# Create core Laravel directories
New-Item -Path "$basePath\Domains" -ItemType Directory
New-Item -Path "$basePath\Exceptions" -ItemType Directory
New-Item -Path "$basePath\Http\Middleware" -ItemType Directory
New-Item -Path "$basePath\Jobs" -ItemType Directory
New-Item -Path "$basePath\Notifications" -ItemType Directory
New-Item -Path "$basePath\Console\Commands" -ItemType Directory

# Create Analytics Domain structure
New-Item -Path "$basePath\Domains\Analytics\Actions" -ItemType Directory
New-Item -Path "$basePath\Domains\Analytics\Commands" -ItemType Directory
New-Item -Path "$basePath\Domains\Analytics\KpiMetric.php" -ItemType File
New-Item -Path "$basePath\Domains\Analytics\FraudPattern.php" -ItemType File
New-Item -Path "$basePath\Domains\Analytics\Actions\CalculateKpis.php" -ItemType File
New-Item -Path "$basePath\Domains\Analytics\Commands\RunAnalyticsReport.php" -ItemType File

# Create Bids Domain structure
New-Item -Path "$basePath\Domains\Bids\Actions" -ItemType Directory
New-Item -Path "$basePath\Domains\Bids\Bid.php" -ItemType File
New-Item -Path "$basePath\Domains\Bids\BidComparison.php" -ItemType File
New-Item -Path "$basePath\Domains\Bids\BidAccepted.php" -ItemType File
New-Item -Path "$basePath\Domains\Bids\BidAcceptedListener.php" -ItemType File
New-Item -Path "$basePath\Domains\Bids\BidStatusEnum.php" -ItemType File
New-Item -Path "$basePath\Domains\Bids\BidsController.php" -ItemType File
New-Item -Path "$basePath\Domains\Bids\BidRequest.php" -ItemType File
New-Item -Path "$basePath\Domains\Bids\UpdateBidRequest.php" -ItemType File
New-Item -Path "$basePath\Domains\Bids\Actions\CreateBid.php" -ItemType File
New-Item -Path "$basePath\Domains\Bids\Actions\UpdateBid.php" -ItemType File
New-Item -Path "$basePath\Domains\Bids\Actions\DeleteBid.php" -ItemType File

# Create Communication Domain structure
New-Item -Path "$basePath\Domains\Communication" -ItemType Directory
New-Item -Path "$basePath\Domains\Communication\Message.php" -ItemType File
New-Item -Path "$basePath\Domains\Communication\Notification.php" -ItemType File
New-Item -Path "$basePath\Domains\Communication\MessageController.php" -ItemType File
New-Item -Path "$basePath\Domains\Communication\NotificationController.php" -ItemType File

# Create Contracts Domain structure
New-Item -Path "$basePath\Domains\Contracts" -ItemType Directory
New-Item -Path "$basePath\Domains\Contracts\Contract.php" -ItemType File
New-Item -Path "$basePath\Domains\Contracts\ContractAmendment.php" -ItemType File
New-Item -Path "$basePath\Domains\Contracts\ContractAudit.php" -ItemType File
New-Item -Path "$basePath\Domains\Contracts\RateSheet.php" -ItemType File
New-Item -Path "$basePath\Domains\Contracts\ContractController.php" -ItemType File

# Create Disputes Domain structure
New-Item -Path "$basePath\Domains\Disputes\Actions" -ItemType Directory
New-Item -Path "$basePath\Domains\Disputes\Dispute.php" -ItemType File
New-Item -Path "$basePath\Domains\Disputes\Actions\ResolveDispute.php" -ItemType File
New-Item -Path "$basePath\Domains\Disputes\DisputeController.php" -ItemType File

# Create Financials Domain structure
New-Item -Path "$basePath\Domains\Financials\Actions" -ItemType Directory
New-Item -Path "$basePath\Domains\Financials\Services" -ItemType Directory
New-Item -Path "$basePath\Domains\Financials\Payments" -ItemType Directory
New-Item -Path "$basePath\Domains\Financials\FinancialReport.php" -ItemType File
New-Item -Path "$basePath\Domains\Financials\Escrow.php" -ItemType File
New-Item -Path "$basePath\Domains\Financials\CurrencyRate.php" -ItemType File
New-Item -Path "$basePath\Domains\Financials\Actions\ProcessPayment.php" -ItemType File
New-Item -Path "$basePath\Domains\Financials\Services\PaymentService.php" -ItemType File
New-Item -Path "$basePath\Domains\Financials\Payments\Payment.php" -ItemType File
New-Item -Path "$basePath\Domains\Financials\Payments\Invoice.php" -ItemType File
New-Item -Path "$basePath\Domains\Financials\Payments\Chargeback.php" -ItemType File
New-Item -Path "$basePath\Domains\Financials\Payments\PaymentController.php" -ItemType File

# Create Freight Domain structure
New-Item -Path "$basePath\Domains\Freight\Actions" -ItemType Directory
New-Item -Path "$basePath\Domains\Freight\FreightController.php" -ItemType File
New-Item -Path "$basePath\Domains\Freight\Freight.php" -ItemType File
New-Item -Path "$basePath\Domains\Freight\FreightAttachment.php" -ItemType File
New-Item -Path "$basePath\Domains\Freight\PriceOptimization.php" -ItemType File
New-Item -Path "$basePath\Domains\Freight\FreightRequest.php" -ItemType File
New-Item -Path "$basePath\Domains\Freight\UpdateFreightRequest.php" -ItemType File
New-Item -Path "$basePath\Domains\Freight\Actions\CreateFreight.php" -ItemType File
New-Item -Path "$basePath\Domains\Freight\Actions\UpdateFreight.php" -ItemType File
New-Item -Path "$basePath\Domains\Freight\Actions\DeleteFreight.php" -ItemType File

# Create Logistics Domain structure
New-Item -Path "$basePath\Domains\Logistics\Services" -ItemType Directory
New-Item -Path "$basePath\Domains\Logistics\Route.php" -ItemType File
New-Item -Path "$basePath\Domains\Logistics\Telematics.php" -ItemType File
New-Item -Path "$basePath\Domains\Logistics\TelematicsController.php" -ItemType File
New-Item -Path "$basePath\Domains\Logistics\Services\TelematicsService.php" -ItemType File

# Create Shipments Domain structure
New-Item -Path "$basePath\Domains\Shipments\Actions" -ItemType Directory
New-Item -Path "$basePath\Domains\Shipments\ShipmentController.php" -ItemType File
New-Item -Path "$basePath\Domains\Shipments\Shipment.php" -ItemType File
New-Item -Path "$basePath\Domains\Shipments\CostAllocation.php" -ItemType File
New-Item -Path "$basePath\Domains\Shipments\CustomsDoc.php" -ItemType File
New-Item -Path "$basePath\Domains\Shipments\HazardousMaterial.php" -ItemType File
New-Item -Path "$basePath\Domains\Shipments\ShipmentAttachment.php" -ItemType File
New-Item -Path "$basePath\Domains\Shipments\ShipmentException.php" -ItemType File
New-Item -Path "$basePath\Domains\Shipments\ShipmentEvent.php" -ItemType File
New-Item -Path "$basePath\Domains\Shipments\ShipmentPrediction.php" -ItemType File
New-Item -Path "$basePath\Domains\Shipments\ShipmentRequirement.php" -ItemType File
New-Item -Path "$basePath\Domains\Shipments\ShipmentVersion.php" -ItemType File
New-Item -Path "$basePath\Domains\Shipments\TemperatureLog.php" -ItemType File
New-Item -Path "$basePath\Domains\Shipments\TrackingUpdate.php" -ItemType File
New-Item -Path "$basePath\Domains\Shipments\ShipmentStatusEnum.php" -ItemType File
New-Item -Path "$basePath\Domains\Shipments\ShipmentStatusUpdated.php" -ItemType File
New-Item -Path "$basePath\Domains\Shipments\UpdateShipmentStatusListener.php" -ItemType File
New-Item -Path "$basePath\Domains\Shipments\Actions\UpdateShipmentStatus.php" -ItemType File

# Create Subscriptions Domain structure
New-Item -Path "$basePath\Domains\Subscriptions" -ItemType Directory
New-Item -Path "$basePath\Domains\Subscriptions\Subscription.php" -ItemType File
New-Item -Path "$basePath\Domains\Subscriptions\SubscriptionPlan.php" -ItemType File
New-Item -Path "$basePath\Domains\Subscriptions\SubscriptionController.php" -ItemType File

# Create Users Domain structure
New-Item -Path "$basePath\Domains\Users\Actions" -ItemType Directory
New-Item -Path "$basePath\Domains\Users\Requests" -ItemType Directory
New-Item -Path "$basePath\Domains\Users\Controllers\Auth" -ItemType Directory
New-Item -Path "$basePath\Domains\Users\Controllers\Password" -ItemType Directory
New-Item -Path "$basePath\Domains\Users\PhoneVerification" -ItemType Directory
New-Item -Path "$basePath\Domains\Users\User.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\Driver.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\UserProfile.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\UserDevice.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\PasswordHistory.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\PhoneVerification.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\UserPreference.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\UserActivityLog.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\ApiKey.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\AuthToken.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\UsersController.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\UserRoleEnum.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\Actions\RegisterUserAction.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\Actions\LoginUserAction.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\Actions\UpdatePasswordAction.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\Actions\ResetPasswordAction.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\Controllers\Auth\LoginController.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\Controllers\Auth\RegisterController.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\Controllers\Password\PasswordResetController.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\Controllers\Password\PasswordController.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\PhoneVerification\VerifyPhoneController.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\PhoneVerification\OtpRequest.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\Requests\LoginRequest.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\Requests\RegisterUserRequest.php" -ItemType File
New-Item -Path "$basePath\Domains\Users\Requests\UpdatePasswordRequest.php" -ItemType File

# Create Vehicles Domain structure
New-Item -Path "$basePath\Domains\Vehicles" -ItemType Directory
New-Item -Path "$basePath\Domains\Vehicles\Vehicle.php" -ItemType File
New-Item -Path "$basePath\Domains\Vehicles\Maintenance.php" -ItemType File
New-Item -Path "$basePath\Domains\Vehicles\FuelLog.php" -ItemType File
New-Item -Path "$basePath\Domains\Vehicles\VehicleController.php" -ItemType File

# Create Exceptions and other top-level files
New-Item -Path "$basePath\Exceptions\BidNotFoundException.php" -ItemType File
New-Item -Path "$basePath\Exceptions\UnauthorizedException.php" -ItemType File
New-Item -Path "$basePath\Http\Middleware\CheckRoleMiddleware.php" -ItemType File
New-Item -Path "$basePath\Http\Middleware\EnsurePhoneVerified.php" -ItemType File
New-Item -Path "$basePath\Jobs\ProcessFinancialReport.php" -ItemType File
New-Item -Path "$basePath\Jobs\SendOtpJob.php" -ItemType File
New-Item -Path "$basePath\Notifications\BidAcceptedNotification.php" -ItemType File
New-Item -Path "$basePath\Notifications\ShipmentArrivedNotification.php" -ItemType File
New-Item -Path "$basePath\Notifications\SendOtpNotification.php" -ItemType File
New-Item -Path "$basePath\Console\Commands\CalculateKpis.php" -ItemType File
