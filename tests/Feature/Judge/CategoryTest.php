<?php

namespace Tests\Feature\Judge;

use App\Category;
use App\Judge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function allRouteProvider()
    {
        return [
            'List' => ['get', 'judge.categories.index'],
            'Item' => ['get', 'judge.categories.show', ['category' => 1]],
            'Edit' => ['get', 'judge.categories.edit', ['category' => 1]],
            'Update' => ['patch', 'judge.categories.update', ['category' => 1]],
            'Get Status' => ['get', 'judge.categories.status'],
            'List Categories' => ['get', 'judge.categories.list-categories'],
        ];
    }

    public function categoryParameterRouteProvider()
    {
        return [
            'Item' => ['get', 'judge.categories.show', ['category' => 1]],
            'Edit' => ['get', 'judge.categories.edit', ['category' => 1]],
            'Update' => ['patch', 'judge.categories.update', ['category' => 1]],
        ];
    }

    public function getMethodRouteProvider()
    {
        return [
            'List' => ['get', 'judge.categories.index'],
            'Item' => ['get', 'judge.categories.show', ['category' => 1]],
            'Edit' => ['get', 'judge.categories.edit', ['category' => 1]],
            'Get Status' => ['get', 'judge.categories.status'],
            'List Categories' => ['get', 'judge.categories.list-categories'],
        ];
    }

    /**
     * @dataProvider allRouteProvider
     *
     * @param mixed $method
     * @param mixed $route
     * @param mixed $parameter
     *
     * @test */
    public function guestValidation($method, $route, $parameter = [])
    {
        // Arrange
        $this->seed();

        // Act
        $response = $this->$method(route($route, $parameter));

        // Assert
        $response->assertRedirect('login');
    }

    /**
     * @dataProvider categoryParameterRouteProvider
     *
     * @param mixed $method
     * @param mixed $route
     * @param mixed $parameter
     *
     * @test */
    public function notExistingCategoryValidation($method, $route, $parameter = [])
    {
        // Arrange
        $this->seed();

        $this->login(null, 'judge');

        // Act
        $response = $this->$method(route($route, $parameter));

        // Assert
        $response->assertStatus(404);
    }

    /**
     * @dataProvider categoryParameterRouteProvider
     *
     * @param mixed $method
     * @param mixed $route
     * @param mixed $parameter
     *
     * @test */
    public function notExistingJudgeInCategoryValidation($method, $route, $parameter)
    {
        // Arrange
        $this->seed();

        factory(Category::class)->create();

        $judge = factory(Judge::class)->create();

        $this->login($judge->user()->first());

        session(['judge' => $judge->id]);

        // Act
        $response = $this->$method(route($route, $parameter));

        // Assert
        $response->assertStatus(404);
    }

    /**
     * @dataProvider allRouteProvider
     *
     * @param mixed $method
     * @param mixed $route
     * @param mixed $parameter
     *
     * @test */
    public function invalidRoleValidation($method, $route, $parameter = [])
    {
        // Arrange
        $this->seed();

        $this->login();

        factory(Category::class)->create();

        // Act
        $response = $this->$method(route($route, $parameter));

        // Assert
        $response->assertStatus(403);
    }

    /** @test */
    public function cannotAccessScoreResultsIfCategoryIsDormant()
    {
        // Arrange
        $this->seed();

        $contest = $this->contestFaker();

        $judge = $contest->judges()->first();

        $category = $contest->categories()->first();

        $this->login($judge->user()->first());

        session(['judge' => $judge->id]);

        // Act
        $response = $this->get(route('judge.categories.show', ['category' => $category->id]));

        // Assert
        $response->assertStatus(403);
    }

    public function confirmLockScoreProvider()
    {
        return [
            'No category score' => ['que'],
            'No criteria score' => ['scoring'],
        ];
    }

    /**
     * @dataProvider confirmLockScoreProvider
     *
     * @param mixed $status
     *
     * @test */
    public function confirmLockScoreValidation($status)
    {
        // Arrange
        $this->seed();

        $contest = $this->contestFaker(1, 1, 1, 1, $status);

        $category = $contest->categories()->first();

        if ('scoring' === $status) {
            $category->categoryScores()->first()->criteriaScores()->first()->delete();
        }

        $judge = $contest->judges()->first();

        $this->login($judge->user()->first());

        session(['judge' => $judge->id]);

        // Act
        $response = $this->get(route('judge.categories.edit', ['category' => $category->id]));

        // Assert
        $response
            ->assertOk()
            ->assertSessionHas('error');
    }

    /** @test */
    public function cannotLockScoreIfJudgeScoreIsAlreadyCompleted()
    {
        // Arrange
        $this->seed();

        $contest = $this->contestFaker(1, 1, 1, 1, 'done');

        $judge = $contest->judges()->first();

        $category = $contest->categories()->first();

        $this->login($judge->user()->first());

        session(['judge' => $judge->id]);

        // Act
        $response = $this->patch(route('judge.categories.update', ['category' => $category->id]));

        // Assert
        $response->assertStatus(403);
    }

    /** @test */
    public function canLockScore()
    {
        // Arrange
        $this->seed();

        $contest = $this->contestFaker(1, 1, 1, 1, 'scoring');

        $category = $contest->categories()->first();

        $judge = $contest->judges()->first();

        $this->login($judge->user()->first());

        session(['judge' => $judge->id]);

        // Act
        $response = $this->patch(route('judge.categories.update', ['category' => $category->id]));

        // Assert
        $response
            ->assertRedirect(route('judge.categories.edit', ['category' => $category->id]))
            ->assertSessionHas('success');
    }

    /**
     * @dataProvider getMethodRouteProvider
     *
     * @param mixed $method
     * @param mixed $route
     * @param mixed $parameter
     *
     * @test */
    public function canAccessCategories($method, $route, $parameter = [])
    {
        // Arrange
        $this->seed();

        $contest = $this->contestFaker(1, 1, 1, 1, 'scoring');

        $judge = $contest->judges()->first();

        $category = $contest->categories()->first();

        $this->login($judge->user()->first());

        session(['judge' => $judge->id]);

        // Act
        $response = $this->$method(route($route, $parameter));

        // Assert
        $response->assertOk();
    }
}
