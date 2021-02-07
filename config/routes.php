<?php

use App\Http\Controller\PostReportController;

return [
    '/posts/reports/the-longest-per-month' => [
        'class' => PostReportController::class,
        'method' => 'getTheLongestPerMonthReport'
    ],
    '/posts/reports/avg-length-per-month' => [
        'class' => PostReportController::class,
        'method' => 'getAvgLengthPerMonthReport'
    ],
    '/posts/reports/avg-count-per-user-per-month' => [
        'class' => PostReportController::class,
        'method' => 'getAvgPostsCountPerMonthPerUserReport'
    ],
    '/posts/reports/total-by-week' => [
        'class' => PostReportController::class,
        'method' => 'getTotalByWeek'
    ],
];

