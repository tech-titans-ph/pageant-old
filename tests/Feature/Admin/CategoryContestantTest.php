<?php

namespace Tests\Feature\Admin;

use App\Category;
use App\Contest;
use App\Contestant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryContestantTest extends TestCase
{
    use RefreshDatabase;

    public function allRouteProvider()
    {
        return [
            'Item - Contestant Score in Category' => ['get', 'admin.contests.categories.category-contestants.show', ['contest' => 1, 'category' => 1, 'categoryContestant' => 1]],
            'Store' => ['post', 'admin.contests.categories.category-contestants.store', ['contest' => 1, 'category' => 1]],
            'Delete' => ['delete', 'admin.contests.categories.category-contestants.destroy', ['contest' => 1, 'category' => 1, 'categoryContestant' => 1]],
        ];
    }

    public function contestAndCategoryParameterRouteProvider()
    {
        return [
            'Item - Contestant Score in Category' => ['get', 'admin.contests.categories.category-contestants.show', ['contest' => 1, 'category' => 1, 'categoryContestant' => 1]],
            'Store' => ['post', 'admin.contests.categories.category-contestants.store', ['contest' => 1, 'category' => 1]],
            'Delete' => ['delete', 'admin.contests.categories.category-contestants.destroy', ['contest' => 1, 'category' => 1, 'categoryContestant' => 1]],
        ];
    }

    public function categoryContestantParameterRouteProvider()
    {
        return [
            'Item - Contestant Score in Category' => ['get', 'admin.contests.categories.category-contestants.show', ['contest' => 1, 'category' => 1, 'categoryContestant' => 1]],
            'Delete' => ['delete', 'admin.contests.categories.category-contestants.destroy', ['contest' => 1, 'category' => 1, 'categoryContestant' => 1]],
        ];
    }

    public function getMethodRouteProvider()
    {
        return [
            'Item - Contestant Score in Category' => ['get', 'admin.contests.categories.category-contestants.show', ['contest' => 1, 'category' => 1, 'categoryContestant' => 1]],
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
     * @dataProvider contestAndCategoryParameterRouteProvider
     *
     * @param mixed $method
     * @param mixed $route
     * @param mixed $parameter
     *
     * @test */
    public function notExistingContestValidation($method, $route, $parameter = [])
    {
        // Arrange
        $this->seed();

        $this->login();

        // Act
        $response = $this->$method(route($route, $parameter));

        // Assert
        $response->assertStatus(404);
    }

    /**
     * @dataProvider contestAndCategoryParameterRouteProvider
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

        $this->login();

        factory(Contest::class)->create();

        // Act
        $response = $this->$method(route($route, $parameter));

        // Assert
        $response->assertStatus(404);
    }

    /**
     * @dataProvider categoryContestantParameterRouteProvider
     *
     * @param mixed $method
     * @param mixed $route
     * @param mixed $parameter
     *
     * @test */
    public function notExistingcategoryContestantValidation($method, $route, $parameter = [])
    {
        // Arrange
        $this->seed();

        $this->login();

        factory(Category::class)->create();

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

        $this->login(null, 'judge');

        $contest = $this->contestFactory();

        // Act
        $response = $this->$method(route($route, $parameter));

        // Assert
        $response->assertStatus(403);
    }

    public function notFinishedCategoryStatusProvider()
    {
        return [
            'Default' => ['que'],
            'Scoring' => ['scoring'],
        ];
    }

    /**
     * @dataProvider notFinishedCategoryStatusProvider
     *
     * @param mixed $status
     *
     * @test */
    public function cannotViewContestantScoreIfCategoryIsNotFinishedScoring($status)
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = $this->contestFactory(1, 1, 1, 1, $status);

        $category = $contest->categories()->first();

        $categoryContestant = $category->categoryContestants()->first();

        // Act
        $response = $this->get(route('admin.contests.categories.category-contestants.show', ['contest' => $contest->id, 'category' => $category->id, 'categoryContestant' => $categoryContestant->id]));

        // Assert
        $response->assertStatus(403);
    }

    /** @test */
    public function cannotAddcategoryContestantIfCategoryIsFinishedScoring()
    {
        // Arrange
        $this->seed();

        $this->login();

        $category = factory(Category::class)->create(['status' => 'done']);

        $contestant = factory(Contestant::class)->create(['contest_id' => $category->contest->id]);

        // Act
        $response = $this->post(route('admin.contests.categories.category-contestants.store', ['contest' => $category->contest->id, 'category' => $category->id]), ['contestant_id' => $contestant->id]);

        // Assert
        $response
            ->assertRedirect(route('admin.contests.categories.show', ['contest' => $category->contest->id, 'category' => $category->id, 'activeTab' => 'Contestants']))
            ->assertSessionHas('error');
    }

    public function contestantInputValidationProvider()
    {
        return [
            'Required' => [['contestant_id' => '']],
            'Exists' => [['contestant_id' => 0]],
            'Exist in Contest' => [['contestant_id' => 2]],
            'Unique' => [['contestant_id' => 1]],
        ];
    }

    /**
     * @dataProvider contestantInputValidationProvider
     *
     * @param mixed $formData
     *
     * @test */
    public function createcategoryContestantFormValidation($formData)
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = factory(Contest::class)->create();

        $contestant = factory(Contestant::class)->create(['contest_id' => $contest->id]);

        $category = factory(Category::class)->create(['contest_id' => $contest->id]);

        $category->categoryContestants()->create(['contestant_id' => $contestant->id]);

        $anotherContest = factory(Contest::class)->create();

        factory(Contestant::class)->create(['contest_id' => $anotherContest->id]);

        // Act
        $response = $this->post(route('admin.contests.categories.category-contestants.store', ['contest' => $contest->id, 'category' => $category->id]), $formData);

        // Assert
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(array_keys($formData)[0]);
    }

    /** @test */
    public function canCreatecategoryContestant()
    {
        // Arrange
        $this->seed();

        $this->login();

        $category = factory(Category::class)->create();

        $contestant = factory(Contestant::class)->create(['contest_id' => $category->contest->id]);

        $data = ['contestant_id' => $contestant->id];

        // Act
        $response = $this->post(route('admin.contests.categories.category-contestants.store', ['contest' => $category->contest->id, 'category' => $category->id]), $data);

        // Assert
        $response
            ->assertRedirect(route('admin.contests.categories.show', ['contest' => $category->contest->id, 'category' => $category->id, 'activeTab' => 'Contestants']))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('category_contestants', ['category_id' => $category->id, 'contestant_id' => $contestant->id]);
    }

    /** @test */
    public function canDeletecategoryContestant()
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = $this->contestFactory();

        // Act
        $response = $this->delete(route('admin.contests.categories.category-contestants.destroy', ['contest' => $contest->id, 'category' => $contest->categories()->first()->id, 'categoryContestant' => $contest->categories()->first()->categoryContestants()->first()->id]));

        // Assert
        $response
            ->assertRedirect(route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $contest->categories()->first()->id, 'activeTab' => 'Contestants']))
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
    public function canAccessCategoryContestants($method, $route, $parameter = [])
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = $this->contestFaker(1, 1, 1, 1, 'done');

        $contest->categories()->first()->update(['status' => 'done']);

        // Act
        $response = $this->$method(route($route, $parameter));

        // Assert
        $response->assertOk();
    }
}
