<?php

namespace App\Interfaces;

interface TaskRepositoryInterface
{
    public function index(array $query);
    public function getById($id);
    public function store(array $data);
    public function update(array $data,$id);
    public function delete($id);
}