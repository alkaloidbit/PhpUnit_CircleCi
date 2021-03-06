<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Dinosaur;
use AppBundle\Entity\Enclosure;
use AppBundle\Entity\Security;
use AppBundle\Factory\DinosaurFactory;
use AppBundle\Service\EnclosureBuilderService;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EnclosureBuilderServiceIntegrationTest extends KernelTestCase
{
    /**
     * undocumented function
     *
     * @return void
     */
    public function setUp()
    {
        self::bootKernel();

        $this->truncateEntities([
            Enclosure::class,
            Security::class,
            Dinosaur::class,
        ]);
    }

    public function testItBuildsEnclosureWithDefaultSpecifications()
    {
        /* $enclosureBuilderService = self::$kernel->getContainer() */
        /*                                         ->get('test.'.EnclosureBuilderService::class); */

        /** @var \AppBundle\Factory\DinosaurFactory&\PHPUnit\Framework\MockObject\MockObject */
        $dinoFactory = $this->createMock(DinosaurFactory::class);
        $dinoFactory->expects($this->any())
                    ->method('growFromSpecification')
                    ->willReturnCallback(function($spec) {
                        return new Dinosaur();
                    });

        $enclosureBuilderService = new EnclosureBuilderService(
            $this->getEntityManager(),
            $dinoFactory
        );

        $enclosureBuilderService->buildEnclosure();

        /** @var EntityManager $em */
        $em = $this->getEntityManager();

        $count = (int) $em->getRepository(Security::class)
                          ->createQueryBuilder('s')
                          ->select('COUNT(s.id)')
                          ->getQuery()
                          ->getSingleScalarResult();

        $this->assertSame(1, $count, 'Amount of security systems is not the same');

        $count = (int) $em->getRepository(Dinosaur::class)
                          ->createQueryBuilder('d')
                          ->select('COUNT(d.id)')
                          ->getQuery()
                          ->getSingleScalarResult();

        $this->assertSame(3, $count, 'Amount of dinosaurs is not the same');

    }

    private function truncateEntities(array $entities)
    {
        $purger = new ORMPurger($this->getEntityManager());
        $purger->purge();
    }

    /**
     * undocumented function
     *
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return self::$kernel->getContainer()
                            ->get('doctrine')
                            ->getManager();
    }

}
