-- Add payment_ref column to booked table if it doesn't exist
ALTER TABLE booked ADD COLUMN IF NOT EXISTS payment_ref VARCHAR(255) DEFAULT NULL;

-- Update existing records to have proper payment status
UPDATE booked SET payment_status = 'paid' WHERE payment_status IS NULL OR payment_status = '';

-- Ensure booking_date column exists with default timestamp
ALTER TABLE booked ADD COLUMN IF NOT EXISTS booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP;