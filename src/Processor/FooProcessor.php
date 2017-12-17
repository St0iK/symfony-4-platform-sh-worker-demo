<?php
namespace App\Processor;

use App\Entity\Post;
use App\Entity\User;
use AppBundle\Calculator\DomainDataCalculator;
use AppBundle\Entity\Domain;
use Enqueue\Client\CommandSubscriberInterface;
use Enqueue\Consumption\QueueSubscriberInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrContext;
use Interop\Queue\PsrProcessor;
use Enqueue\Client\TopicSubscriberInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Article;

class FooProcessor implements PsrProcessor, TopicSubscriberInterface
{
    /**
     * @var ManagerRegistry
     */
    private $registry;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        \Doctrine\Common\Persistence\ManagerRegistry $registry,
        LoggerInterface $logger
    )
    {
        $this->registry = $registry;
        $this->logger = $logger;
    }

    /**
     * @param PsrMessage $message
     * @param PsrContext $session
     * @return object|string
     */
    public function process(PsrMessage $message, PsrContext $session)
    {
        $em = $this->registry->getManager();
        $postRepo = $em->getRepository(Post::class);

        $author = new User();
        $author->setEmail('test'.rand().'@test.com');
        $author->setFullName('he he');
        $author->setUsername('he_he'.rand());
        $author->setPassword('he_he');
        $em->persist($author);
        $em->flush();

        $post = new Post();
        $post->setTitle('Hey ' . rand());
        $post->setSlug('my-slug'. rand());
        $post->setSummary('mmmm');
        $post->setContent('mmmm');
        $post->setContent('mmmm');
        $post->setAuthor($author);

        $em->persist($post);
        $em->flush();

        $data = json_decode($message->getBody(), true);

        try {
            // do some stuff
            $this->logger->critical('lets log something');

        } catch (\Exception $exception) {
            echo $exception->getMessage();
            return self::REJECT;
        }

        return self::ACK;
    }

    public static function getSubscribedTopics()
    {
        return ['aTopic', 'anotherTopic'];
    }
}
