<?php

namespace Tests\Feature\Admin;

use App\Category;
use App\Contest;
use App\Judge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryJudgeTest extends TestCase
{
    use RefreshDatabase;

    public function allRouteProvider()
    {
        return [
            'Store' => ['post', 'admin.contests.categories.category-judges.store', ['contest' => 1, 'category' => 1]],
            'Delete' => ['delete', 'admin.contests.categories.category-judges.destroy', ['contest' => 1, 'category' => 1, 'categoryJudge' => 1]],
        ];
    }

    public function contestAndCategoryParameterRouteProvider()
    {
        return [
            'Store' => ['post', 'admin.contests.categories.category-judges.store', ['contest' => 1, 'category' => 1]],
        ];
    }

    public function categoryJudgeParameterRouteProvider()
    {
        return [
            'Delete' => ['delete', 'admin.contests.categories.category-judges.destroy', ['contest' => 1, 'category' => 1, 'categoryJudge' => 1]],
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
     * @dataProvider categoryJudgeParameterRouteProvider
     *
     * @param mixed $method
     * @param mixed $route
     * @param mixed $parameter
     *
     * @test */
    public function notExistingCategoryJudgeValidation($method, $route, $parameter = [])
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

    /** @test */
    public function cannotAddCategoryJudgeIfCategoryIsFinishedScoring()
    {
        // Arrange
        $this->seed();

        $this->login();

        $category = factory(Category::class)->create(['status' => 'done']);

        $judge = factory(Judge::class)->create(['contest_id' => $category->contest->id]);

        // Act
        $response = $this->post(route('admin.contests.categories.category-judges.store', ['contest' => $category->contest->id, 'category' => $category->id]), ['judge_id' => $judge->id]);

        // Assert
        $response
            ->assertRedirect(route('admin.contests.categories.show', ['contest' => $category->contest->id, 'category' => $category->id, 'activeTab' => 'Judges']))
            ->assertSessionHas('error');
    }

    public function judgeInputValidationProvider()
    {
        return [
            'Required' => [['judge_id' => '']],
            'Exists' => [['judge_id' => 0]],
            'Exist in Contest' => [['judge_id' => 2]],
            'Unique' => [['judge_id' => 1]],
        ];
    }

    /**
     * @dataProvider judgeInputValidationProvider
     *
     * @param mixed $formData
     *
     * @test */
    public function createCategoryJudgeFormValidation($formData)
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = factory(Contest::class)->create();

        $judge = factory(Judge::class)->create(['contest_id' => $contest->id]);

        $category = factory(Category::class)->create(['contest_id' => $contest->id]);

        $category->categoryJudges()->create(['judge_id' => $judge->id]);

        $anotherContest = factory(Contest::class)->create();

        factory(Judge::class)->create(['contest_id' => $anotherContest->id]);

        // Act
        $response = $this->post(route('admin.contests.categories.category-judges.store', ['contest' => $contest->id, 'category' => $category->id]), $formData);

        // Assert
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(array_keys($formData)[0]);
    }

    /** @test */
    public function canCreateCategoryJudge()
    {
        // Arrange
        $this->seed();

        $this->login();

        $category = factory(Category::class)->create();

        $judge = factory(Judge::class)->create(['contest_id' => $category->contest->id]);

        $data = ['judge_id' => $judge->id];

        // Act
        $response = $this->post(route('admin.contests.categories.category-judges.store', ['contest' => $category->contest->id, 'category' => $category->id]), $data);

        // Assert
        $response
            ->assertRedirect(route('admin.contests.categories.show', ['contest' => $category->contest->id, 'category' => $category->id, 'activeTab' => 'Judges']))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('category_judges', ['category_id' => $category->id, 'judge_id' => $judge->id]);
    }

    /** @test */
    public function canDeleteCategoryJudge()
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = $this->contestFactory();

        // Act
        $response = $this->delete(route('admin.contests.categories.category-judges.destroy', ['contest' => $contest->id, 'category' => $contest->categories()->first()->id, 'categoryJudge' => $contest->categories()->first()->categoryJudges()->first()->id]));

        // Assert
        $response
            ->assertRedirect(route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $contest->categories()->first()->id, 'activeTab' => 'Judges']))
            ->assertSessionHas('success');
    }
}
