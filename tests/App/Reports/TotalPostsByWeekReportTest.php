<?php


namespace Tests\App\Reports;


use App\Report\TotalPostsByWeekReport;
use App\Repository\InMemoryPostRepository;
use Framework\Cache\InMemoryCache;
use Tests\TestCase;

class TotalPostsByWeekReportTest extends TestCase
{

    private array $posts;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->posts = $this->getDummyPosts(10);
    }

    /**
     * @throws \Exception
     */
    public function testGenerate()
    {
        $report = new TotalPostsByWeekReport(new InMemoryPostRepository($this->posts), new InMemoryCache());
        $result = $report->generate();

        foreach (range(0, 12) as $number) {
            echo $number;
        }
        $expected = array_fill(1, 53, 0);
        $expected[1] = 10;

        self::assertEquals($expected, $result);
    }
}