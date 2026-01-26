<?php

return [
    // limits to prevent spam
    'max_per_type' => 50,

    // Renewal reminders at these offsets (days before)
    'renewal_days_before' => [7, 3, 0],

    // Invoice reminders at these offsets (days before)
    'invoice_days_before' => [7, 3, 0],

    // Overdue invoice repeat frequency (days)
    // 1 = daily, 3 = every 3 days
    'overdue_repeat_days' => 1,

    // Scheduler time (server will run schedule:run each minute, this is the daily trigger time)
    'daily_time' => '09:00',
];
