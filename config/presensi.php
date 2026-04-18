<?php

return [
    'work_start' => env('WORK_START_TIME', '08:00'),
    'work_end' => env('WORK_END_TIME', '17:00'),
    'late_tolerance' => (int) env('LATE_TOLERANCE_MINUTES', 15),
    'office' => [
        'latitude' => (float) env('OFFICE_LATITUDE', -6.2088),
        'longitude' => (float) env('OFFICE_LONGITUDE', 106.8456),
        'radius' => (int) env('OFFICE_RADIUS_METERS', 100),
    ],
];
