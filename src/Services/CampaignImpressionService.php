<?php
namespace Leadgen\Services;

use Leadgen\LeadgenClient;

class CampaignImpressionService
{
    private $client;

    public function __construct(LeadgenClient $client)
    {
        $this->client = $client;
    }

    public function create(array $data)
    {
        $campaignId = intval($data['campaign_id']);
        return $this->client->request("campaigns/{$campaignId}/impressions", 'POST', $data);
    }
}