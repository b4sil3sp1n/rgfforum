<?php

namespace App\Tests\Controller;

use App\Entity\Artist;
use App\Entity\Event;
use App\Entity\Mix;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SiteTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);

        // Base de test vierge à chaque test
        $tool = new SchemaTool($this->em);
        $tool->dropDatabase();
        $tool->createSchema($this->em->getMetadataFactory()->getAllMetadata());

        $this->em->persist(
            (new Event())->setTitle('Dub Test')->setDate(new \DateTimeImmutable('+1 week'))->setPlace('Cave')->setDescription('Test')
        );
        $this->em->persist((new Artist())->setName('Selecta Test')->setRole('Selecta')->setStyle('Dub'));
        $this->em->persist((new Mix())->setTitle('Mix Test')->setArtist('Selecta Test')->setDuration('60 min'));
        $this->em->flush();
    }

    private function loginAs(string $role): void
    {
        $user = (new User())->setEmail('test-'.strtolower($role).'@rgf.fr')->setRoles([$role]);
        $user->setPassword('inutile');
        $this->em->persist($user);
        $this->em->flush();
        $this->client->loginUser($user);
    }

    public function testPublicPagesAreVisible(): void
    {
        foreach (['/', '/event', '/event/1', '/artist', '/mix', '/login'] as $url) {
            $this->client->request('GET', $url);
            $this->assertResponseIsSuccessful("Échec sur $url");
        }

        $this->client->request('GET', '/event');
        $this->assertSelectorTextContains('table.forumline', 'Dub Test');
    }

    public function testUnknownEventIs404(): void
    {
        $this->client->request('GET', '/event/999');
        $this->assertResponseStatusCodeSame(404);
    }

    public function testAnonymousCannotReachAdmin(): void
    {
        foreach (['/admin', '/admin/api/users', '/event/new', '/artist/new', '/mix/new'] as $url) {
            $this->client->request('GET', $url);
            $this->assertTrue(
                $this->client->getResponse()->isRedirect() || 403 === $this->client->getResponse()->getStatusCode(),
                "$url doit être protégé"
            );
        }
    }

    public function testSimpleUserCannotReachAdmin(): void
    {
        $this->loginAs('ROLE_USER');
        $this->client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(403);
        $this->client->request('GET', '/event/new');
        $this->assertResponseStatusCodeSame(403);
    }

    public function testAdminApiDoesNotLeakPasswords(): void
    {
        $this->loginAs('ROLE_ADMIN');
        $this->client->request('GET', '/admin/api/users');
        $this->assertResponseIsSuccessful();
        $json = $this->client->getResponse()->getContent();
        $this->assertStringContainsString('test-role_admin@rgf.fr', $json);
        $this->assertStringNotContainsString('inutile', $json);
        $this->assertStringNotContainsString('password', $json);
    }

    public function testAdminCanCreateEditAndDeleteAnEvent(): void
    {
        $this->loginAs('ROLE_ADMIN');

        // Création
        $this->client->request('GET', '/event/new');
        $this->client->submitForm('Enregistrer', [
            'event[title]' => 'Nouvelle danse',
            'event[date]' => '2027-01-15T21:00',
            'event[place]' => 'Hangar',
            'event[description]' => 'Une nuit de dub.',
        ]);
        $this->assertResponseRedirects();
        $event = $this->em->getRepository(Event::class)->findOneBy(['title' => 'Nouvelle danse']);
        $this->assertNotNull($event);

        // Formulaire invalide : le titre est obligatoire
        $this->client->request('GET', '/event/'.$event->getId().'/edit');
        $this->client->submitForm('Enregistrer', ['event[title]' => '']);
        $this->assertResponseStatusCodeSame(422);

        // Suppression via le bouton de la page détail
        $this->client->request('GET', '/event/'.$event->getId());
        $this->client->submitForm('Supprimer');
        $this->assertResponseRedirects('/event');
        $this->em->clear();
        $this->assertNull($this->em->getRepository(Event::class)->findOneBy(['title' => 'Nouvelle danse']));
    }

    public function testDeleteWithoutCsrfTokenDoesNothing(): void
    {
        $this->loginAs('ROLE_ADMIN');
        $this->client->request('POST', '/mix/1/delete');
        $this->assertResponseRedirects('/mix');
        $this->assertSame(1, $this->em->getRepository(Mix::class)->count([]));
    }

    public function testLoginWithRealPassword(): void
    {
        $hasher = static::getContainer()->get('security.user_password_hasher');
        $user = (new User())->setEmail('operator@test.fr')->setRoles(['ROLE_ADMIN']);
        $user->setPassword($hasher->hashPassword($user, 'secret'));
        $this->em->persist($user);
        $this->em->flush();

        $this->client->request('GET', '/login');
        $this->client->submitForm('Connexion', ['email' => 'operator@test.fr', 'password' => 'secret']);
        $this->client->followRedirect();
        $this->client->request('GET', '/admin');
        $this->assertResponseIsSuccessful();
    }
}
