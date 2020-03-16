<?php

namespace Tests\Feature\Judge;

use App\Category;
use App\Judge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContestantTest extends TestCase
{
    use RefreshDatabase;

    public function allRouteProvider()
    {
        return [
            'List' => ['get', 'judge.categories.contestants.index', ['category' => 1]],
            'Update' => ['patch', 'judge.categories.contestants.update', ['category' => 1, 'contestant' => 1]],
        ];
    }

    public function contestantParameterRouteProvider()
    {
        return [
            'Update' => ['patch', 'judge.categories.contestants.update', ['category' => 1, 'contestant' => 1]],
        ];
    }

    public function getMethodRouteProvider()
    {
        return [
            'List' => ['get', 'judge.categories.contestants.index', ['category' => 1]],
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
     * @dataProvider allRouteProvider
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
     * @dataProvider allRouteProvider
     *
     * @param mixed $method
     * @param mixed $route
     * @param mixed $parameter
     *
     * @test */
    public function notExistingJudgeInCategoryValidation($method, $route, $parameter = [])
    {
        // Arrange
        $this->seed();

        $contest = $this->contestFaker();

        $judge = factory(Judge::class)->create();

        $this->login($judge->user()->first());

        session(['judge' => $judge->id]);

        // Act
        $response = $this->$method(route($route, $parameter), ['criteria_id' => 1, 'score' => 1]);

        // Assert
        $response->assertStatus(404);
    }

    /**
     * @dataProvider contestantParameterRouteProvider
     *
     * @param mixed $method
     * @param mixed $route
     * @param mixed $parameter
     *
     * @test */
    public function notExistingContestantValidation($method, $route, $parameter = [])
    {
        // Arrange
        $this->seed();

        $contest = $this->contestFaker();

        $judge = $contest->judges()->first();

        $category = $contest->categories()->first();

        $criteria = $category->criterias()->first();

        $contestant = $contest->contestants()->first();

        $contestant->categoryContestants()->first()->delete();

        $this->login($judge->user()->first());

        session(['judge' => $judge->id]);

        // Act
        $response = $this->$method(route($route, $parameter), ['criteria_id' => $criteria->id, 'score' => 1]);

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
    public function InvalidRoleValidation($method, $route, $parameter = [])
    {
        // Arrange
        $this->seed();

        $contest = $this->contestFaker();

        $this->login();

        session(['judge' => 1]);

        // Act
        $response = $this->$method(route($route, $parameter), ['criteria_id' => 1, 'score' => 1]);

        // Assert
        $response->assertStatus(403);
    }

    public function scoreStatusProvider()
    {
        return [
            'Dormant' => ['que', 0],
            'Scoring and Judge Completed' => ['scoring', 1],
            'Finished' => ['done', 1],
        ];
    }

    /** @test */
    public function cannotAccessContestantsIfCategoryIsDormant()
    {
        // Arrange
        $this->seed();

        $contest = $this->contestFaker();

        $judge = $contest->judges()->first();

        $category = $contest->categories()->first();

        $this->login($judge->user()->first());

        session(['judge' => $judge->id]);

        // Act
        $response = $this->get(route('judge.categories.contestants.index', ['category' => $category->id]));

        // Assert
        $response->assertStatus(403);
    }

    /**
     * @dataProvider scoreStatusProvider
     *
     * @param mixed $status
     * @param mixed $completed
     *
     * @test */
    public function setScoreValidation($status, $completed)
    {
        // Arrange
        $this->seed();

        $contest = $this->contestFaker(1, 1, 1, 1, $status);

        $judge = $contest->judges()->first();

        $judge->categoryJudges()->first()->update(['completed' => $completed]);

        $contestant = $contest->contestants()->first();

        $category = $contest->categories()->first();

        $criteria = $category->criterias()->first();

        $this->login($judge->user()->first());

        session(['judge' => $judge->id]);

        // Act
        $response = $this->patch(route('judge.categories.contestants.update', ['category' => $category->id, 'contestant' => $contestant->id]), ['criteria_id' => $criteria->id, 'score' => 1]);

        // Assert
        $response->assertStatus(403);
    }

    public function criteriaInputValidationProvider()
    {
        return [
            'Required' => [['criteria_id' => '']],
            'Exists' => [['criteria_id' => 0]],
            'Exists in category' => [['criteria_id' => 2]],
        ];
    }

    public function scoreInputValidationProvider()
    {
        return [
            'Required' => [['score' => '', 'criteria_id' => 1]],
            'Integer' => [['score' => 1.5, 'criteria_id' => 1]],
            'Minimum of 1' => [['score' => 0, 'criteria_id' => 1]],
            'Maximum of percentage in criteria' => [['score' => 3, 'criteria_id' => 1]],
        ];
    }

    /**
     * @dataProvider criteriaInputValidationProvider
     * @dataProvider scoreInputValidationProvider
     *
     * @param mixed $formData
     *
     * @test */
    public function setScoreFormValidation($formData)
    {
        // Arrange
        $this->seed();

        $contest = $this->contestFaker(1, 1, 2, 1, 'scoring');

        $judge = $contest->judges()->first();

        $contestant = $contest->contestants()->first();

        $category = $contest->categories()->first();

        $category->criterias()->first()->update(['percentage' => 2]);

        $this->login($judge->user()->first());

        session(['judge' => $judge->id]);

        // Act
        $response = $this->patch(route('judge.categories.contestants.update', ['category' => $category->id, 'contestant' => $contestant->id]), $formData);

        // Assert
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(array_keys($formData)[0]);
    }

    /**
     * @dataProvider getMethodRouteProvider
     *
     * @param mixed $method
     * @param mixed $route
     * @param mixed $parameter
     *
     * @test */
    public function canAccessContestants($method, $route, $parameter = [])
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

    /** @test */
    public function canSetScore()
    {
        // Arrange
        $this->seed();

        $contest = $this->contestFaker(1, 1, 1, 1, 'scoring');

        $judge = $contest->judges()->first();

        $category = $contest->categories()->first();

        $contestant = $contest->contestants()->first();

        $data = ['criteria_id' => $category->criterias()->first()->id, 'score' => 1];

        $this->login($judge->user()->first());

        session(['judge' => $judge->id]);

        // Act
        $response = $this->patch(route('judge.categories.contestants.update', ['category' => $category->id, 'contestant' => $contestant->id]), $data);

        // Assert
        $response
            ->assertOk()
            ->assertJson([
                'totalScore' => 1,
            ]);
    }
}
