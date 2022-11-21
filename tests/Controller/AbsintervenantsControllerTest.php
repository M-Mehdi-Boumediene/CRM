<?php

namespace App\Test\Controller;

use App\Entity\Absintervenants;
use App\Repository\AbsintervenantsRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbsintervenantsControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private AbsintervenantsRepository $repository;
    private string $path = '/absintervenants/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Absintervenants::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Absintervenant index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'absintervenant[date]' => 'Testing',
            'absintervenant[created_at]' => 'Testing',
            'absintervenant[created_by]' => 'Testing',
            'absintervenant[du]' => 'Testing',
            'absintervenant[au]' => 'Testing',
            'absintervenant[absent]' => 'Testing',
            'absintervenant[dateabsence]' => 'Testing',
            'absintervenant[enretard]' => 'Testing',
            'absintervenant[dateretard]' => 'Testing',
            'absintervenant[present]' => 'Testing',
            'absintervenant[datepresence]' => 'Testing',
            'absintervenant[userid]' => 'Testing',
            'absintervenant[intervenant]' => 'Testing',
            'absintervenant[module]' => 'Testing',
            'absintervenant[classe]' => 'Testing',
            'absintervenant[user]' => 'Testing',
            'absintervenant[tableau]' => 'Testing',
        ]);

        self::assertResponseRedirects('/absintervenants/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Absintervenants();
        $fixture->setDate('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setCreated_by('My Title');
        $fixture->setDu('My Title');
        $fixture->setAu('My Title');
        $fixture->setAbsent('My Title');
        $fixture->setDateabsence('My Title');
        $fixture->setEnretard('My Title');
        $fixture->setDateretard('My Title');
        $fixture->setPresent('My Title');
        $fixture->setDatepresence('My Title');
        $fixture->setUserid('My Title');
        $fixture->setIntervenant('My Title');
        $fixture->setModule('My Title');
        $fixture->setClasse('My Title');
        $fixture->setUser('My Title');
        $fixture->setTableau('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Absintervenant');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Absintervenants();
        $fixture->setDate('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setCreated_by('My Title');
        $fixture->setDu('My Title');
        $fixture->setAu('My Title');
        $fixture->setAbsent('My Title');
        $fixture->setDateabsence('My Title');
        $fixture->setEnretard('My Title');
        $fixture->setDateretard('My Title');
        $fixture->setPresent('My Title');
        $fixture->setDatepresence('My Title');
        $fixture->setUserid('My Title');
        $fixture->setIntervenant('My Title');
        $fixture->setModule('My Title');
        $fixture->setClasse('My Title');
        $fixture->setUser('My Title');
        $fixture->setTableau('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'absintervenant[date]' => 'Something New',
            'absintervenant[created_at]' => 'Something New',
            'absintervenant[created_by]' => 'Something New',
            'absintervenant[du]' => 'Something New',
            'absintervenant[au]' => 'Something New',
            'absintervenant[absent]' => 'Something New',
            'absintervenant[dateabsence]' => 'Something New',
            'absintervenant[enretard]' => 'Something New',
            'absintervenant[dateretard]' => 'Something New',
            'absintervenant[present]' => 'Something New',
            'absintervenant[datepresence]' => 'Something New',
            'absintervenant[userid]' => 'Something New',
            'absintervenant[intervenant]' => 'Something New',
            'absintervenant[module]' => 'Something New',
            'absintervenant[classe]' => 'Something New',
            'absintervenant[user]' => 'Something New',
            'absintervenant[tableau]' => 'Something New',
        ]);

        self::assertResponseRedirects('/absintervenants/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDate());
        self::assertSame('Something New', $fixture[0]->getCreated_at());
        self::assertSame('Something New', $fixture[0]->getCreated_by());
        self::assertSame('Something New', $fixture[0]->getDu());
        self::assertSame('Something New', $fixture[0]->getAu());
        self::assertSame('Something New', $fixture[0]->getAbsent());
        self::assertSame('Something New', $fixture[0]->getDateabsence());
        self::assertSame('Something New', $fixture[0]->getEnretard());
        self::assertSame('Something New', $fixture[0]->getDateretard());
        self::assertSame('Something New', $fixture[0]->getPresent());
        self::assertSame('Something New', $fixture[0]->getDatepresence());
        self::assertSame('Something New', $fixture[0]->getUserid());
        self::assertSame('Something New', $fixture[0]->getIntervenant());
        self::assertSame('Something New', $fixture[0]->getModule());
        self::assertSame('Something New', $fixture[0]->getClasse());
        self::assertSame('Something New', $fixture[0]->getUser());
        self::assertSame('Something New', $fixture[0]->getTableau());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Absintervenants();
        $fixture->setDate('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setCreated_by('My Title');
        $fixture->setDu('My Title');
        $fixture->setAu('My Title');
        $fixture->setAbsent('My Title');
        $fixture->setDateabsence('My Title');
        $fixture->setEnretard('My Title');
        $fixture->setDateretard('My Title');
        $fixture->setPresent('My Title');
        $fixture->setDatepresence('My Title');
        $fixture->setUserid('My Title');
        $fixture->setIntervenant('My Title');
        $fixture->setModule('My Title');
        $fixture->setClasse('My Title');
        $fixture->setUser('My Title');
        $fixture->setTableau('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/absintervenants/');
    }
}
