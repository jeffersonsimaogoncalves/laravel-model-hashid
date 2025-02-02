<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Traits;

use Str;
use Illuminate\Foundation\Testing\WithFaker;
use Deligoez\LaravelModelHashId\Support\Config;
use Deligoez\LaravelModelHashId\Tests\TestCase;
use Deligoez\LaravelModelHashId\Support\Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;
use Deligoez\LaravelModelHashId\Support\ConfigParameters;

class HasHasIdTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    // region Trait Initialization

    /** @test
     * @throws \Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException
     */
    public function model_hashId_salt_can_be_defined(): void
    {
        // 1️⃣ Arrange 🏗
        /** @var ModelA $model */
        $model = ModelA::factory()->create();
        $hash = $model->hashId;

        // 2️⃣ Act 🏋🏻‍
        Config::set(ConfigParameters::SALT, Str::random());

        // 3️⃣ Assert ✅
        $newHash = ModelA::findOrFail($model->getKey())->hashId;
        $this->assertNotEquals($hash, $newHash);
    }

    /** @test */
    public function model_hashId_length_can_be_defined(): void
    {
        // 1️⃣ Arrange 🏗
        $randomLength = $this->faker->numberBetween(5, 20);
        Config::set(ConfigParameters::LENGTH, $randomLength);

        $model = ModelA::factory()->create();

        // 2️⃣ Act 🏋🏻‍
        $hashId = $model->hashId;

        // 3️⃣ Assert ✅
        $length = mb_strlen(Config::get(ConfigParameters::SEPARATOR)) +
            Config::get(ConfigParameters::PREFIX_LENGTH) +
            $randomLength;

        $this->assertEquals($length, mb_strlen($hashId));
    }

    /** @test */
    public function model_hashId_alphabet_can_be_defined(): void
    {
        // 1️⃣ Arrange 🏗
        $customAlphabet = 'abcdef1234567890';
        Config::set(ConfigParameters::ALPHABET, $customAlphabet);

        $model = ModelA::factory()->create();

        // 2️⃣ Act 🏋🏻‍
        $hashId = $model->hashId;

        // 3️⃣ Assert ✅
        $modelHashId = Generator::parseHashIdForModel($hashId);

        $alphabetAsArray = mb_str_split($customAlphabet);
        foreach (mb_str_split($modelHashId->hashIdForKey) as $char) {
            $this->assertContains($char, $alphabetAsArray);
        }
    }

    /** @test */
    public function model_hashId_alphabet_can_also_be_defined_from_emojis(): void
    {
        // 1️⃣ Arrange 🏗
        $customAlphabet = '😀😃😄😁😆😅😂🤣🥲☺️😊😇🙂🙃😉😌';
        Config::set(ConfigParameters::ALPHABET, $customAlphabet);

        $model = ModelA::factory()->create();

        // 2️⃣ Act 🏋🏻‍
        $hashId = $model->hashId;

        // 3️⃣ Assert ✅
        $modelHashID = Generator::parseHashIDForModel($hashId);

        $alphabetAsArray = mb_str_split($customAlphabet);
        foreach (mb_str_split($modelHashID->hashIdForKey) as $char) {
            $this->assertContains($char, $alphabetAsArray);
        }
    }

    // endregion

    // region Trait Static Functions

    /** @test */
    public function it_can_get_a_model_key_from_hashId(): void
    {
        // 1️⃣ Arrange 🏗
        $model = ModelA::factory()->create();
        $hashId = $model->hashId;

        // 2️⃣ Act 🏋🏻‍
        $key = ModelA::keyFromHashId($hashId);

        // 3️⃣ Assert ✅
        $this->assertEquals($model->getKey(), $key);
    }

    /** @test */
    public function it_returns_null_if_hashId_can_not_parsable(): void
    {
        // 2️⃣ Act 🏋🏻‍
        $key = ModelA::keyFromHashId('non-existing-hash-id');

        // 3️⃣ Assert ✅
        $this->assertNull($key);
    }

    // endregion

    // region Accessors

    /** @test */
    public function model_has_a_hashId_attribute(): void
    {
        // 1️⃣ Arrange 🏗
        $model = ModelA::factory()->create();

        // 2️⃣ Act 🏋🏻‍
        $hashId = $model->hashId;
        $key = $model->getKey();

        // 3️⃣ Assert ✅
        $this->assertEquals($key, ModelA::keyFromHashId($hashId));
    }

    /** @test */
    public function model_has_a_hashIdRaw_attribute(): void
    {
        // 1️⃣ Arrange 🏗
        $model = ModelA::factory()->create();

        // 2️⃣ Act 🏋🏻‍
        $hashIdRawAttribute = $model->hashIdRaw;

        // 3️⃣ Assert ✅
        $hashIdRaw = Generator::parseHashIDForModel($model->hashId)->hashIdForKey;
        $this->assertEquals($hashIdRaw, $hashIdRawAttribute);
    }

    /** @test */
    public function it_returns_null_if_model_does_not_have_a_key_for_hashIdRaw(): void
    {
        // 1️⃣ Arrange 🏗
        /** @var ModelA $model */
        $model = ModelA::factory()->make();

        // 2️⃣ Act 🏋🏻‍
        $hashIdRawAttribute = $model->hashIdRaw;

        // 3️⃣ Assert ✅
        $this->assertNull($hashIdRawAttribute);
    }

    // endregion
}
