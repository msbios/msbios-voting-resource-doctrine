<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Voting\Resource\Doctrine\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use MSBios\Doctrine\ObjectManagerAwareTrait;
use MSBios\Voting\Resource\Doctrine\Entity\Poll;
use MSBios\Voting\Resource\Doctrine\Entity\Vote;
use MSBios\Voting\Resource\Doctrine\Repository\Poll\PollRelationRepository;
use MSBios\Voting\Resource\Doctrine\Repository\PollRepository;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\RequestInterface;

/**
 * Class RelationController
 * @package MSBios\Voting\Resource\Doctrine\Controller
 */
class RelationController extends AbstractActionController implements ObjectManagerAwareInterface
{
    use ObjectManagerAwareTrait;

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository()
    {
        /** @var ObjectManager $dem */
        $dem = $this->getObjectManager();

        /** @var PollRepository $repository */
        return $dem->getRepository(Poll\PollRelation::class);
    }

    /**
     *
     */
    public function indexAction()
    {

        /** @var RequestInterface $request */
        $request = $this->getRequest();

        // Make sure that we are running in a console, and the user has not
        // tricked our application into running this action from a public web
        // server:

        if (! $request instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        /** @var array $polls */
        $polls = $this->getRepository()->findAll();

        /** @var Poll $poll */
        foreach ($polls as $poll) {
            printf(
                "ID:%s; Subject: %s; Relation: %s;\n",
                $poll->getId(),
                $poll->getSubject(),
                $poll->getCode()
            );
        }
    }

    /**
     *
     */
    public function votesAction()
    {
        /** @var RequestInterface $request */
        $request = $this->getRequest();

        // Make sure that we are running in a console, and the user has not
        // tricked our application into running this action from a public web
        // server:

        if (! $request instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        /** @var int $id */
        $id = $request->getParam('pollid');

        /** @var PollRelationRepository $repository */
        $repository = $this->getRepository();

        if (! ($poll = $repository->find($id))) {
            exit("The wrong value was specified, the entry with such \"{$id}\" does not exist.\n");
        }

        /** @var array $votes */
        $votes = $repository->findVotesBy($poll);

        printf(
            "ID:%s; Subject: %s; Code: %s;\n",
            $poll->getId(),
            $poll->getSubject(),
            $poll->getCode()
        );

        /** @var Vote $vote */
        foreach ($votes as $vote) {
            printf("ID:%s; Name:%s; Vote: %s;\n", $vote['id'], $vote['name'], $vote['vote']);
        }
    }
}
