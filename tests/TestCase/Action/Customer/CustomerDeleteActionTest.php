<?php

namespace App\Test\TestCase\Action\Project;

use App\Test\Fixture\ProjectFixture;
use App\Test\Traits\AppTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

/**
 * Test.
 *
 * @coversDefaultClass \App\Action\Project\ProjectDeleterAction
 */
class ProjectDeleteActionTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;

    public function testDeleteProject(): void
    {
        $this->insertFixtures([ProjectFixture::class]);

        $request = $this->createJsonRequest('DELETE', '/api/Projects/1');

        $response = $this->app->handle($request);

        // Check response
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());

        // Check database
        $this->assertTableRowCount(1, 'Projects');
        $this->assertTableRowNotExists('Projects', 1);
    }
}
