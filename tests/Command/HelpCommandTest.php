<?php

namespace Rvdv\Nntp\Tests\Command;

use Rvdv\Nntp\Command\HelpCommand;
use Rvdv\Nntp\Response\Response;

/**
 * @author Robin van der Vleuten <robinvdvleuten@gmail.com>
 */
class HelpCommandTest extends CommandTest
{
    public function testItExpectsMultilineResponses()
    {
        $command = $this->createCommandInstance();
        $this->assertTrue($command->isMultiLine());
    }

    public function testItNotExpectsCompressedResponses()
    {
        $command = $this->createCommandInstance();
        $this->assertFalse($command->isCompressed());
    }

    public function testItHasDefaultResult()
    {
        $command = $this->createCommandInstance();
        $this->assertEmpty($command->getResult());
    }

    public function testItReturnsStringWhenExecuting()
    {
        $command = $this->createCommandInstance();
        $this->assertEquals('HELP', $command->execute());
    }

    public function testItReceivesAResultWhenInformationFollowsResponse()
    {
        $command = $this->createCommandInstance();

        $response = $this->getMockBuilder('Rvdv\Nntp\Response\MultiLineResponse')
            ->disableOriginalConstructor()
            ->getMock();

        $lines = array('body [MessageID|Number]', 'date', 'head [MessageID|Number]', 'help', 'ihave');

        $response->expects($this->once())
            ->method('getLines')
            ->will($this->returnValue($lines));

        $command->onHelpTextFollow($response);

        $result = $command->getResult();
        $this->assertEquals(implode("\n", $lines), $result);
    }

    /**
     * {@inheritdoc}
     */
    protected function createCommandInstance()
    {
        return new HelpCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getRFCResponseCodes()
    {
        return array(
            Response::HELP_TEXT_FOLLOWS,
        );
    }
}
