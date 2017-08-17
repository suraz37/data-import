<?php

use App\Demo\Transformer\CsvTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Demo\Transformer\CsvTransformer
 */
class CsvTransformerTest extends TestCase
{
    /**
     * Transformer
     *
     * @var
     */
    private $transformer;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->transformer = $this->createMock(CsvTransformer::class);
    }

    /**
     * Transform
     *
     * @covers ::transform
     */
    public function testTransformParameterAndReturn()
    {
        $parameter = new LimitIterator(new ArrayIterator([1, 2, 3, 4]), 0, 4);

        $this->transformer
            ->method('transform')
            ->willReturn([]);

        $this->assertEquals([], $this->transformer->transform($parameter));
    }
}
