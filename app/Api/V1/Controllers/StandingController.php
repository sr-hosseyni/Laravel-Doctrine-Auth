<?php

namespace Tournament\API\V1\Controllers;

use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tournament\API\V1\Repositories\GroupRepository;
use Tournament\API\V1\Transformers\MatchesTransformer;
use Tournament\Core\MatchGenerator;

class StandingController extends APIController
{
    /**
     *
     * @var GroupRepository
     */
    private $groupRepository;

    /**
     *
     * @var EntityManager
     */
    private $em;

    public function __construct(
        GroupRepository $groupRepository,
        EntityManager $em
    )
    {
        $this->groupRepository = $groupRepository;
        $this->em = $em;
    }

    /**
     * Generate all matches for given group id
     *
     * @param  Request  $request
     * @param  int  $groupId
     * @return Response
     */
    public function summary($groupId)
    {
        try {
            $standings = $this->groupRepository->standing($groupId);

            return $this->apiResponseSucces($standings);
        } catch (\Exception $ex) {
            return $this->apiResponseError($ex->getMessage());
        }
    }
}
