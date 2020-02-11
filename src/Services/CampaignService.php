<?php
namespace Leadgen\Services;

use Leadgen\LeadgenClient;

class CampaignService
{
    private $client;

    public function __construct(LeadgenClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function create(array $data): array
    {
        return $this->client->request('campaigns', 'POST', $data);
    }

    /**
     * @param string $code
     *
     * @return array|null
     */
    public function findByCode(string $code): ?array
    {
        $list = $this->getList(['code' => $code]);

        return count($list['data']) > 0 ? $list['data'][0] : null;
    }

    /**
     * @param array $data
     *
     * @return array|null
     */
    public function getList(array $data = []): ?array
    {
        return $this->client->request('campaigns', 'GET', $data);
    }

    /**
     * @param $id
     * @param $data
     *
     * @return array
     */
    public function update($id, $data): array
    {
        return $this->client->request('campaigns', 'PUT', $data);
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        return $this->client->request('campaigns/' . $id, 'DELETE');
    }
}