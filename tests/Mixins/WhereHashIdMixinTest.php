<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Mixins;

use Deligoez\LaravelModelHashId\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;

class WhereHashIdMixinTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_query_a_model_by_its_hashId(): void
    {
        // 1️⃣ Arrange 🏗
        $model = ModelA::factory()->create();

        // 2️⃣ Act 🏋🏻‍
        $foundModel = ModelA::query()
                            ->whereHashId($model->hashId)
                            ->first();

        // 3️⃣ Assert ✅
        $this->assertTrue($model->is($foundModel));
    }
}
