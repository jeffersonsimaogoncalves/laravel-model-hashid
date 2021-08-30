<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Mixins;

use Deligoez\LaravelModelHashIDs\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Deligoez\LaravelModelHashIDs\Tests\Models\ModelA;

class FindOrNewByHashIDMixinTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_find_or_new_a_model_by_its_hashID(): void
    {
        // 2️⃣ Act 🏋🏻‍
        /** @var ModelA $newModel */
        $newModel = ModelA::findOrNewByHashID('non-existing-hash-id');

        // 3️⃣ Assert ✅
        $this->assertFalse($newModel->exists);
    }
}
