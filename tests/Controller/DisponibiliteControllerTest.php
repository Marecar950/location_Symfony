<?php

namespace App\Test\Controller;

use App\Entity\Disponibilite;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DisponibiliteControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/disponibilite/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Disponibilite::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Disponibilite index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'disponibilite[dateDebut]' => 'Testing',
            'disponibilite[dateFin]' => 'Testing',
            'disponibilite[prixParJour]' => 'Testing',
            'disponibilite[statut]' => 'Testing',
            'disponibilite[vehicule]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Disponibilite();
        $fixture->setDateDebut('My Title');
        $fixture->setDateFin('My Title');
        $fixture->setPrixParJour('My Title');
        $fixture->setStatut('My Title');
        $fixture->setVehicule('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Disponibilite');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Disponibilite();
        $fixture->setDateDebut('Value');
        $fixture->setDateFin('Value');
        $fixture->setPrixParJour('Value');
        $fixture->setStatut('Value');
        $fixture->setVehicule('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'disponibilite[dateDebut]' => 'Something New',
            'disponibilite[dateFin]' => 'Something New',
            'disponibilite[prixParJour]' => 'Something New',
            'disponibilite[statut]' => 'Something New',
            'disponibilite[vehicule]' => 'Something New',
        ]);

        self::assertResponseRedirects('/disponibilite/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDateDebut());
        self::assertSame('Something New', $fixture[0]->getDateFin());
        self::assertSame('Something New', $fixture[0]->getPrixParJour());
        self::assertSame('Something New', $fixture[0]->getStatut());
        self::assertSame('Something New', $fixture[0]->getVehicule());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Disponibilite();
        $fixture->setDateDebut('Value');
        $fixture->setDateFin('Value');
        $fixture->setPrixParJour('Value');
        $fixture->setStatut('Value');
        $fixture->setVehicule('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/disponibilite/');
        self::assertSame(0, $this->repository->count([]));
    }
}
