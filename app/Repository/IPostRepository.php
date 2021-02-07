<?php

namespace App\Repository;

use App\Entity\Post;


interface IPostRepository
{
    public function all();
    public function getByMonth(string $monthNumber);
    public function getByUserId(string $userId);
    public function save(Post $post): Post;
    public function update(Post $post): Post;
    public function fillThePostObject(array $data): Post;

}
