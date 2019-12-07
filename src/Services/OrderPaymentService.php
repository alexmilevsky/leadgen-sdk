<?php
namespace Leadgen\Services;

use Leadgen\LeadgenClient;

class OrderPaymentService
{
    private $client;

    public function __construct(LeadgenClient $client)
    {
        $this->client = $client;
    }

    public function create(array $data)
    {
        $orderId = intval($data['order_id']);
        return $this->client->request("orders/{$orderId}/payments", 'POST', $data);
    }
}