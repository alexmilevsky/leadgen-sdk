<?php
namespace Leadgen\Services;

use Leadgen\LeadgenClient;

class StatisticService
{
    private $client;

    public function __construct(LeadgenClient $client)
    {
        $this->client = $client;
    }

    public function getList(array $data = [])
    {
        return $this->client->request('statistics', 'GET', $data);
    }
}