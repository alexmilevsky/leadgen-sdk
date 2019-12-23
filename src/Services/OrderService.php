<?php
namespace Leadgen\Services;

use Leadgen\LeadgenClient;

class OrderService
{
    private $client;

    public function __construct(LeadgenClient $client)
    {
        $this->client = $client;
    }

    public function create(array $data)
    {
        return $this->client->request('orders', 'POST', $data);
    }

    public function update($id, array $data)
    {
        $url = 'orders/' . $id;
        return $this->client->request($url, 'PATCH', $data);
    }
}