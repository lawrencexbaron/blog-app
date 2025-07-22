<?php


namespace App\Repositories\Interfaces;

interface PostRepositoryInterface
{
    public function all();
    public function find($id, $connection);
    public function create(array $data, $connection);
    public function update($id, array $data, $connection);
    public function delete($id);
    public function search(string $keyword);
}
