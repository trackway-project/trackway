<?php

namespace AppBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AbstractControllerTest
 *
 * @package AppBundle\Test\Controller\User
 */
abstract class AbstractControllerTest extends WebTestCase
{
    protected static $defaultFixtures = ['AppBundle\DataFixtures\ORM\LoadAbsenceReasons', 'AppBundle\DataFixtures\ORM\LoadGroups', 'AppBundle\DataFixtures\ORM\LoadInvitationStatuses', 'AppBundle\DataFixtures\ORM\LoadLocales', 'AppBundle\Tests\DataFixtures\ORM\LoadTestLocale'];
    protected static $userFixtures = ['AppBundle\Tests\DataFixtures\ORM\LoadUser'];
    protected static $usersFixtures = ['AppBundle\Tests\DataFixtures\ORM\LoadUsers'];
    protected static $teamFixtures = ['AppBundle\Tests\DataFixtures\ORM\LoadTeam', 'AppBundle\Tests\DataFixtures\ORM\LoadMembershipOwner', 'AppBundle\Tests\DataFixtures\ORM\LoadActiveTeam'];
    protected static $projectFixtures = ['AppBundle\Tests\DataFixtures\ORM\LoadProject'];
    protected static $taskFixtures = ['AppBundle\Tests\DataFixtures\ORM\LoadTask'];
    protected static $timeEntryFixtures = ['AppBundle\Tests\DataFixtures\ORM\LoadTimeEntry'];
    protected static $absenceFixtures = ['AppBundle\Tests\DataFixtures\ORM\LoadAbsence'];
    protected static $membershipFixtures = ['AppBundle\Tests\DataFixtures\ORM\LoadMembership'];
    protected static $invitationFixtures = ['AppBundle\Tests\DataFixtures\ORM\LoadInvitation'];

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
    public static function assertFlashMessage(Crawler $crawler, $text, $message = 'Unexpected flash message.', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
    {
        if (getenv('OUTPUT_RESPONSE_ON_FAILURE')) {
            $message .= ' Response:' . PHP_EOL . $crawler->html();
        }
        static::assertEquals(1, $crawler->filter('div.alert:contains("' . $text . '")')->count(), $message, $delta, $maxDepth, $canonicalize, $ignoreCase);
    }

    /**
     * @param Client $client
     * @param string $message
     * @param float $delta
     * @param int $maxDepth
     * @param bool $canonicalize
     * @param bool $ignoreCase
     */
    public static function assertStatusCode(Client $client, $message = 'Unexpected HTTP status code.', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
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
