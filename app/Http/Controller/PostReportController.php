<?php

namespace App\Http\Controller;


use App\Report\AvgLengthOfPostsPerMonthReport;
use App\Report\AvgPostsCountPerUserPerMonthReport;
use App\Report\LongestPostPerMonthReport;
use App\Report\TotalPostsByWeekReport;
use App\Repository\SqlPostRepository;
use Exception;
use Framework\Cache\ICache;
use Framework\Http\Response\JsonResponse;

class PostReportController {
    private SqlPostRepository $postRepository;
    private ICache $cache;

    /**
     * PostReportController constructor.
     * @param  SqlPostRepository  $postRepository
     * @param  ICache  $cache
     */
    public function __construct(SqlPostRepository $postRepository, ICache $cache)
    {
        $this->postRepository = $postRepository;
        $this->cache = $cache;
    }

    /**
     * @return string
     */
    public function getTheLongestPerMonthReport(): string
    {
        $report = new LongestPostPerMonthReport($this->postRepository, $this->cache);
        return JsonResponse::send($report->generate());
    }

    /**
     * @return string
     */
    public function getAvgLengthPerMonthReport(): string
    {
        $report = new AvgLengthOfPostsPerMonthReport($this->postRepository, $this->cache);
        return JsonResponse::send($report->generate());
    }

    /**
     * @return string
     */
    public function getAvgPostsCountPerMonthPerUserReport(): string
    {
        $report = new AvgPostsCountPerUserPerMonthReport($this->postRepository, $this->cache);
        return JsonResponse::send($report->generate());
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getTotalByWeek(): string
    {
        $report = new TotalPostsByWeekReport($this->postRepository, $this->cache);
        return JsonResponse::send($report->generate());
    }
}
