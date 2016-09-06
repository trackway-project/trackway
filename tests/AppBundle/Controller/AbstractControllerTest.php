<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AbstractControllerTest
 *
 * @package Tests\AppBundle\Controller\User
 */
abstract class AbstractControllerTest extends WebTestCase
{
    protected static $defaultFixtures = ['AppBundle\DataFixtures\ORM\LoadAbsenceReasons',
        'AppBundle\DataFixtures\ORM\LoadGroups',
        'AppBundle\DataFixtures\ORM\LoadInvitationStatuses',
        'AppBundle\DataFixtures\ORM\LoadLocales',
        'Tests\AppBundle\DataFixtures\ORM\LoadTestLocale'];
    protected static $userFixtures = ['Tests\AppBundle\DataFixtures\ORM\LoadUser'];
    protected static $usersFixtures = ['Tests\AppBundle\DataFixtures\ORM\LoadUsers'];
    protected static $teamFixtures = ['Tests\AppBundle\DataFixtures\ORM\LoadTeam',
        'Tests\AppBundle\DataFixtures\ORM\LoadMembershipOwner',
        'Tests\AppBundle\DataFixtures\ORM\LoadActiveTeam'];
    protected static $projectFixtures = ['Tests\AppBundle\DataFixtures\ORM\LoadProject'];
    protected static $timeEntryFixtures = ['Tests\AppBundle\DataFixtures\ORM\LoadTimeEntry'];
    protected static $absenceFixtures = ['Tests\AppBundle\DataFixtures\ORM\LoadAbsence'];
    protected static $membershipFixtures = ['Tests\AppBundle\DataFixtures\ORM\LoadMembership'];
    protected static $invitationFixtures = ['Tests\AppBundle\DataFixtures\ORM\LoadInvitation'];

    /**
     * @param Crawler $crawler
     * @param string $text
     * @param string $message
     * @param float $delta
     * @param int $maxDepth
     * @param bool $canonicalize
     * @param bool $ignoreCase
     */
    public static function assertHeadline(Crawler $crawler, $text, $message = 'Unexpected headline.', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
    {
        if (getenv('OUTPUT_RESPONSE_ON_FAILURE')) {
            $message .= ' Response:' . PHP_EOL . $crawler->html();
        }
        static::assertEquals(1, $crawler->filter('h1:contains("' . $text . '")')->count(), $message, $delta, $maxDepth, $canonicalize, $ignoreCase);
    }

    /**
     * @param Crawler $crawler
     * @param string $text
     * @param string $message
     * @param float $delta
     * @param int $maxDepth
     * @param bool $canonicalize
     * @param bool $ignoreCase
     */
    public static function assertMessage(Crawler $crawler, $text, $message = 'Unexpected message.', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
    {
        if (getenv('OUTPUT_RESPONSE_ON_FAILURE')) {
            $message .= ' Response:' . PHP_EOL . $crawler->html();
        }
        static::assertEquals(1, $crawler->filter('p:contains("' . $text . '")')->count(), $message, $delta, $maxDepth, $canonicalize, $ignoreCase);
    }

    /**
     * @param Crawler $crawler
     * @param string $text
     * @param string $message
     * @param float $delta
     * @param int $maxDepth
     * @param bool $canonicalize
     * @param bool $ignoreCase
     */
    public static function assertFlashMessage(Crawler $crawler, $text, $message = 'Unexpected flash message.', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
    {
        if (getenv('OUTPUT_RESPONSE_ON_FAILURE')) {
            $message .= ' Response:' . PHP_EOL . $crawler->html();
        }
        static::assertEquals(1, $crawler->filter('div.alert:contains("' . $text . '")')->count(), $message, $delta, $maxDepth, $canonicalize, $ignoreCase);
    }

    /**
     * @param Crawler $crawler
     * @param string $text
     * @param string $message
     * @param float $delta
     * @param int $maxDepth
     * @param bool $canonicalize
     * @param bool $ignoreCase
     */
    public static function assertNotification(Crawler $crawler, $text, $message = 'Unexpected flash message.', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
    {
        if (getenv('OUTPUT_RESPONSE_ON_FAILURE')) {
            $message .= ' Response:' . PHP_EOL . $crawler->html();
        }
        static::assertEquals(1, $crawler->filter('.notifications-menu .menu a:contains("' . $text . '")')->count(), $message, $delta, $maxDepth, $canonicalize, $ignoreCase);
    }

    /**
     * @param Client $client
     * @param string $message
     * @param float $delta
     * @param int $maxDepth
     * @param bool $canonicalize
     * @param bool $ignoreCase
     */
    public static function assertStatusCodeCustom(Client $client, $message = 'Unexpected HTTP status code.', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
    {
        if (getenv('OUTPUT_RESPONSE_ON_FAILURE')) {
            $message .= ' Response:' . PHP_EOL . $client->getResponse()->getContent();
        }
        static::assertEquals(200, $client->getResponse()->getStatusCode(), $message, $delta, $maxDepth, $canonicalize, $ignoreCase);
    }

    /**
     * @var Client
     */
    protected $client = null;

    protected function setUp()
    {
        $this->client = static::createClient();
        $this->client->followRedirects(true);
        $this->client->setMaxRedirects(10);
    }

    /**
     * @param string $username
     * @param string $password
     */
    protected function login($username = 'test', $password = 'test')
    {
        $this->client = static::makeClient(['username' => $username, 'password' => $password]);
        $this->client->followRedirects(true);
        $this->client->setMaxRedirects(10);
    }
}
