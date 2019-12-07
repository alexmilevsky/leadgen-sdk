<?php
namespace Leadgen\Services;

use Leadgen\LeadgenClient;

class ProjectService
{
    private $client;

    public function __construct(LeadgenClient $client)
    {
        $this->client = $client;
    }

    public function create(array $data)
    {
        return $this->client->request('projects', 'POST', $data);
    }

    public function getList(array $data = [])
    {
        return $this->client->request('projects', 'GET', $data);
    }
}