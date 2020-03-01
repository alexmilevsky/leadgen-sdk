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
        if (isset($data['order_ids'])) {
            return $this->client->request('statistics', 'POST', $data);
        }
        return $this->client->request('statistics', 'GET', $data);
    }

    public function getInputList(array $data = [])
    {
        if (isset($data['order_ids'])) {
            return $this->client->request('input-statistics', 'POST', $data);
        }
        return $this->client->request('input-statistics', 'GET', $data);
    }
}