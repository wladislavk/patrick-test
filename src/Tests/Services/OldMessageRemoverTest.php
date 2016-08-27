<?php
namespace Tests\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Services\OldMessageRemover;

class OldMessageRemoverTest extends \PHPUnit_Framework_TestCase
{
    private $entityManager;
    private $entityRepository;

    private $messages;

    public function setUp()
    {
        $this->messages = array_fill(0, 10, 'foo');
    }

    public function testWith10Messages()
    {
        $this->mockEntityRepository();
        $this->mockEntityManager();
        $oldMessageRemover = new OldMessageRemover($this->entityManager);
        $oldMessageRemover->remove();
        $this->assertEquals(10, sizeof($this->messages));
    }

    public function testWithMoreThan10Messages()
    {
        $this->messages[] = 'bar';
        $this->messages[] = 'baz';
        $this->mockEntityRepository();
        $this->mockEntityManager();
        $oldMessageRemover = new OldMessageRemover($this->entityManager);
        $oldMessageRemover->remove();
        $this->assertEquals(10, sizeof($this->messages));
        $this->assertEquals(array_fill(0, 10, 'foo'), $this->messages);
    }

    private function mockEntityRepository()
    {
        $this->entityRepository = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->entityRepository->expects($this->any())
            ->method('findBy')
            ->will($this->returnValue($this->messages));
    }

    private function mockEntityManager()
    {
        $this->entityManager = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->entityManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($this->entityRepository));
        $this->entityManager->expects($this->any())
            ->method('remove')
            ->will($this->returnCallback([$this, 'removeEntityCallback']));
        $this->entityManager->expects($this->any())
            ->method('flush')
            ->will($this->returnValue(null));
    }

    public function removeEntityCallback($entity)
    {
        if ($key = array_search($entity, $this->messages)) {
            unset($this->messages[$key]);
        }
    }
}
