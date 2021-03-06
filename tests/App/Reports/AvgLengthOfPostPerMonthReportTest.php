<?php


namespace Tests\App\Reports;


use App\Entity\Post;
use App\Report\AvgLengthOfPostsPerMonthReport;
use App\Repository\InMemoryPostRepository;
use App\Repository\SqlPostRepository;
use Framework\Cache\InMemoryCache;
use Tests\TestCase;

class AvgLengthOfPostPerMonthReportTest extends TestCase
{

    private array $posts;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->posts = $this->getDummyPosts(1); // text length === 4
    }

    /**
     * @throws \Exception
     */
    public function testGenerate()
    {
        $post = new Post();
        $post->text = '12345678';
        $date = new \DateTime('2020-01-01');
        $post->created_at = $date->format(SqlPostRepository::DATE_FORMAT);
        $this->posts[] = $post;

        $report = new AvgLengthOfPostsPerMonthReport(new InMemoryPostRepository($this->posts), new InMemoryCache());
        $result = $report->generate();

        $expected = [
            'Jan' => 6,
            'Feb' => 0,
            'Mar' => 0,
            'Apr' => 0,
            'May' => 0,
            'Jun' => 0,
            'Jul' => 0,
            'Aug' => 0,
            'Sep' => 0,
            'Oct' => 0,
            'Nov' => 0,
            'Dec' => 0,
        ];


        self::assertEquals($expected, $result);
    }
}
