<?php

use Illuminate\Support\Facades\Schedule;

// Check overdue borrowings daily at 8:00 AM
Schedule::command('borrowings:check-overdue')->dailyAt('08:00');
