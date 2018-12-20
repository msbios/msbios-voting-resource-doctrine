<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Voting\Resource\Doctrine\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Persistence\ProvidesObjectManager;
use MSBios\Voting\Resource\Doctrine\Entity\Poll;
use MSBios\Voting\Resource\Doctrine\Entity\Vote;
use MSBios\Voting\Resource\Doctrine\Repository\PollRepository;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\RequestInterface;

/**
 * Class PollController
 * @package MSBios\Voting\Resource\Doctrine\Controller
 */
class PollController extends AbstractActionController implements ObjectManagerAwareInterface
{
    use ProvidesObjectManager;

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository()
    {
        /** @var ObjectManager $dem */
        $dem = $this->getObjectManager();

        /** @var PollRepository $repository */
        return $dem->getRepository(Poll::class);
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
            echo sprintf("ID:%s; Subject: %s;\n", $poll->getId(), $poll->getSubject());
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

        /** @var PollRepository $repository */
        $repository = $this->getRepository();

        if (! ($poll = $repository->find($id))) {
            exit("The wrong value was specified, the entry with such \"{$id}\" does not exist.\n");
        }

        /** @var array $votes */
        $votes = $repository->findVotesBy($poll);

        printf("ID:%s; Subject: %s;\n", $poll->getId(), $poll->getSubject());

        /** @var Vote $vote */
        foreach ($votes as $vote) {
            printf("ID:%s; Name:%s; Vote: %s;\n", $vote['id'], $vote['name'], $vote['vote']);
        }
    }
}
