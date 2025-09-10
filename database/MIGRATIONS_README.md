# Database Migrations Documentation

This document provides an overview of all database migrations for the FreightFlow application, organized by domain and dependency order.

## Migration Order

The migrations are designed to be run in the following order to handle foreign key dependencies correctly:

### 1. Core User Management
- `0001_01_01_000000_create_users_table.php` - Base users table (updated to remove role column for Spatie)
- `2025_01_01_000002_create_user_profiles_table.php` - User profile information
- `2025_01_01_000003_create_user_preferences_table.php` - User preferences and settings
- `2025_01_01_000004_create_user_devices_table.php` - User device tracking
- `2025_01_01_000005_create_user_activity_logs_table.php` - User activity logging
- `2025_01_01_000006_create_api_keys_table.php` - API key management
- `2025_01_01_000007_create_auth_tokens_table.php` - Authentication tokens
- `2025_01_01_000008_create_audit_logs_table.php` - System audit logging

### 2. Fleet Management
- `2025_01_01_000009_create_drivers_table.php` - Driver information
- `2025_01_01_000010_create_vehicles_table.php` - Vehicle information
- `2025_01_01_000011_create_maintenance_table.php` - Vehicle maintenance records
- `2025_01_01_000012_create_fuel_logs_table.php` - Fuel consumption tracking
- `2025_01_01_000013_create_telematics_table.php` - Vehicle telematics data
- `2025_01_01_000014_create_routes_table.php` - Route definitions

### 3. Freight Management
- `2025_01_01_000015_create_freight_table.php` - Freight load information
- `2025_01_01_000016_create_freight_attachments_table.php` - Freight attachments
- `2025_01_01_000017_create_price_optimization_table.php` - Price optimization data

### 4. Bidding System
- `2025_01_01_000018_create_bids_table.php` - Bid submissions
- `2025_01_01_000019_create_bid_comparisons_table.php` - Bid comparison analytics

### 5. Shipment Management
- `2025_01_01_000020_create_shipments_table.php` - Shipment tracking
- `2025_01_01_000021_create_shipment_attachments_table.php` - Shipment documents
- `2025_01_01_000022_create_shipment_events_table.php` - Shipment event tracking
- `2025_01_01_000023_create_shipment_exceptions_table.php` - Shipment exceptions
- `2025_01_01_000024_create_shipment_versions_table.php` - Shipment versioning
- `2025_01_01_000025_create_shipment_requirements_table.php` - Special requirements
- `2025_01_01_000026_create_shipment_predictions_table.php` - AI predictions
- `2025_01_01_000027_create_tracking_updates_table.php` - Real-time tracking
- `2025_01_01_000028_create_temperature_logs_table.php` - Temperature monitoring
- `2025_01_01_000029_create_hazardous_materials_table.php` - Hazardous materials
- `2025_01_01_000030_create_customs_docs_table.php` - Customs documentation
- `2025_01_01_000031_create_cost_allocations_table.php` - Cost tracking

### 6. Contract Management
- `2025_01_01_000032_create_contracts_table.php` - Contract agreements
- `2025_01_01_000033_create_contract_amendments_table.php` - Contract amendments
- `2025_01_01_000034_create_contract_audits_table.php` - Contract compliance
- `2025_01_01_000035_create_accessorial_charges_table.php` - Additional charges
- `2025_01_01_000036_create_rate_sheets_table.php` - Rate structures

### 7. Financial Management
- `2025_01_01_000037_create_invoices_table.php` - Invoice generation
- `2025_01_01_000038_create_payments_table.php` - Payment processing
- `2025_01_01_000039_create_chargebacks_table.php` - Payment disputes
- `2025_01_01_000040_create_escrow_table.php` - Escrow management
- `2025_01_01_000041_create_currency_rates_table.php` - Exchange rates
- `2025_01_01_000042_create_financial_reports_table.php` - Financial reporting

### 8. Analytics & Security
- `2025_01_01_000043_create_kpi_metrics_table.php` - KPI tracking
- `2025_01_01_000044_create_fraud_patterns_table.php` - Fraud detection
- `2025_01_01_000045_create_data_feeds_table.php` - External data feeds

### 9. Communication & Reviews
- `2025_01_01_000046_create_disputes_table.php` - Dispute resolution
- `2025_01_01_000047_create_messages_table.php` - Internal messaging
- `2025_01_01_000048_create_ratings_reviews_table.php` - Rating system

### 10. Subscription Management
- `2025_01_01_000049_create_subscription_plans_table.php` - Subscription plans
- `2025_01_01_000050_create_subscriptions_table.php` - User subscriptions

## Key Changes from Original Schema

1. **Removed Role Column**: The `role` column has been removed from the users table since you're using Spatie for role management.

2. **Updated Field Names**: 
   - `phone_number` → `contact_number` in users table
   - `bigIncrements` → `id()` for consistency

3. **Added Missing Tables**: All tables from your database schema have been included with proper foreign key relationships.

## Running Migrations

To run all migrations:

```bash
php artisan migrate
```

To rollback all migrations:

```bash
php artisan migrate:rollback
```

To refresh migrations (rollback and migrate):

```bash
php artisan migrate:refresh
```

## Important Notes

1. **Spatie Integration**: Make sure you have Spatie Laravel Permission package installed and configured before running migrations.

2. **Geometry Columns**: Some tables use PostGIS geometry columns (`point`, `linestring`). Ensure your database supports spatial data types.

3. **JSON Columns**: Several tables use JSON columns for flexible data storage. Ensure your database supports JSON data types.

4. **Foreign Key Constraints**: All foreign keys are properly defined with appropriate cascade/set null behaviors.

5. **Indexes**: Consider adding indexes on frequently queried columns for better performance.

## Domain Structure Alignment

These migrations align with your new domain-driven file structure:

- **Users Domain**: User management, profiles, preferences, devices, activity logs
- **Vehicles Domain**: Vehicle management, maintenance, fuel logs, telematics
- **Freight Domain**: Freight management, attachments, price optimization
- **Bids Domain**: Bidding system, bid comparisons
- **Shipments Domain**: Shipment tracking, events, exceptions, requirements
- **Contracts Domain**: Contract management, amendments, audits
- **Financials Domain**: Invoices, payments, escrow, currency rates
- **Analytics Domain**: KPI metrics, fraud patterns, data feeds
- **Communication Domain**: Messages, disputes, ratings
- **Subscriptions Domain**: Subscription plans and user subscriptions

## Next Steps

1. Run the migrations in order
2. Set up Spatie roles and permissions
3. Create seeders for initial data
4. Implement the domain models and services
5. Set up proper indexes for performance optimization



