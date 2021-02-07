<?php
namespace App\Console;

use App\Entity\Post;
use App\Exceptions\InvalidApiResponseException;
use App\Provider\AppServiceProvider;
use App\Repository\IPostRepository;
use App\Repository\SqlPostRepository;
use App\Service\ApiService;
use DateTime;
use Exception;
use Framework\Cache\Cache;
use Framework\Console\ConsoleOutput;
use Framework\Console\ICommand;
use Framework\Exceptions\CannotInstantiateClassException;
use Framework\Exceptions\FileNotFoundException;
use Framework\Exceptions\UnknownCacheDriverException;
use Framework\Exceptions\UnknownDatabaseDriverException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Client\ClientExceptionInterface;

class ParsePostsFromApiCommand implements ICommand
{
    /** @var IPostRepository $postRepository */
    private IPostRepository $postRepository;
    /**
     * @throws CannotInstantiateClassException
     * @throws FileNotFoundException
     * @throws UnknownCacheDriverException
     * @throws UnknownDatabaseDriverException
     * @throws InvalidApiResponseException
     * @throws GuzzleException
     * @throws ClientExceptionInterface
     */
    public function execute(): void
    {
        $app = new AppServiceProvider();

        $this->postRepository = $app->get(SqlPostRepository::class);

        /** @var ApiService $apiService */
        $apiService = $app->get(ApiService::class);

        $cache = new Cache();

        ConsoleOutput::info('Start requesting and loading data from API to DB...');

        $olds = $this->postRepository->all();
        $page = 1;
        $inserts = 0;
        $updates = 0;

        while(true) {
            ConsoleOutput::info("Handling page $page ...");
            $postsData = $apiService->getData($page);
            if ($postsData['page'] !== $page || count($postsData['posts']) === 0) {
                break;
            }

            $counters = $this->handlePosts($olds, $postsData['posts']);
            $inserts += $counters['insert'];
            $updates += $counters['update'];
            $page++;
        }

        --$page;

        if ($inserts > 0 || $updates > 0) {
            $cache->flush();
        }

        ConsoleOutput::success('Data was loaded successfully! There were handled ' . $page . ' api pages.');
        ConsoleOutput::success("There were $inserts inserts and $updates updated in DB");
    }

    /**
     * @param  array  $olds
     * @param  array  $new
     * @return array
     * @throws Exception
     */
    private function handlePosts(array $olds, array $new): array
    {
        $counters = ['insert' => 0, 'update' => 0];
        foreach ($new as $post) {
            $entity = $this->fillThePostObject($post);
            foreach ($olds as $old) {
                if ($old->id === $entity->id && $old != $entity) {
                    $counters['update']++;
                    $this->postRepository->update($entity);
                    continue 2;
                }

                if ($old->id === $entity->id && $old == $entity) {
                    continue 2;
                }
            }
            $counters['insert']++;
            $this->postRepository->save($entity);
        }

        return $counters;
    }

    /**
     * @param  array  $post
     * @return Post
     * @throws Exception
     */
    private function fillThePostObject(array $post): Post
    {
        $entity = new Post();
        $entity->id = $post['id'];
        $entity->user_id = $post['from_id'];
        $entity->user_name = $post['from_name'];
        $entity->text = $post['message'];
        $date = new DateTime($post['created_time']);
        $entity->created_at = $date->format(SqlPostRepository::DATE_FORMAT);

        return $entity;
    }
}
