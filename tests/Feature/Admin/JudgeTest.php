<?php

namespace Tests\Feature\Admin;

use App\Category;
use App\CategoryJudge;
use App\Contest;
use App\Judge;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class JudgeTest extends TestCase
{
    use RefreshDatabase;

    public function allRouteProvider()
    {
        return [
            'Select' => ['get', 'admin.judges.index'],
            'Store' => ['post', 'admin.contests.judges.store', ['contest' => 1]],
            'Edit' => ['get', 'admin.contests.judges.edit', ['contest' => 1, 'judge' => 1]],
            'Update' => ['patch', 'admin.contests.judges.update', ['contest' => 1, 'judge' => 1]],
            'Delete' => ['delete', 'admin.contests.judges.destroy', ['contest' => 1, 'judge' => 1]],
            'Login' => ['get', 'admin.contests.judges.login', ['contest' => 1, 'judge' => 1]],
        ];
    }

    public function contestParamterRouteProvider()
    {
        return [
            'Store' => ['post', 'admin.contests.judges.store', ['contest' => 1]],
            'Edit' => ['get', 'admin.contests.judges.edit', ['contest' => 1, 'judge' => 1]],
            'Update' => ['patch', 'admin.contests.judges.update', ['contest' => 1, 'judge' => 1]],
            'Delete' => ['delete', 'admin.contests.judges.destroy', ['contest' => 1, 'judge' => 1]],
        ];
    }

    public function judgeParameterRouteProvider()
    {
        return [
            'Edit' => ['get', 'admin.contests.judges.edit', ['contest' => 1, 'judge' => 1]],
            'Update' => ['patch', 'admin.contests.judges.update', ['contest' => 1, 'judge' => 1]],
            'Delete' => ['delete', 'admin.contests.judges.destroy', ['contest' => 1, 'judge' => 1]],
            'Login' => ['get', 'admin.contests.judges.login', ['contest' => 1, 'judge' => 1]],
        ];
    }

    public function getMethodRouteProvider()
    {
        return [
            'Select' => ['get', 'admin.judges.index'],
            'Edit' => ['get', 'admin.contests.judges.edit', ['contest' => 1, 'judge' => 1]],
            'Login' => ['get', 'admin.contests.judges.login', ['contest' => 1, 'judge' => 1]],
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
     * @dataProvider contestParamterRouteProvider
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
     * @dataProvider judgeParameterRouteProvider
     *
     * @param mixed $method
     * @param mixed $route
     *
     * @test */
    public function notExistingJudgeValidation($method, $route)
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = factory(Contest::class)->create();

        $judge = factory(Judge::class)->create();

        // Act
        $response = $this->$method(route($route, ['contest' => $contest->id, 'judge' => $judge->id]), ['name' => 'Judge Name']);

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

        factory(Judge::class)->create();

        // Act
        $response = $this->$method(route($route, $parameter));

        // Assert
        $response->assertStatus(403);
    }

    public function userIdInputValidationProvider()
    {
        return [
            'Required' => [['user_id' => '']],
            'Unique' => [['user_id' => 2]],
            'Exists' => [['user_id' => 0]],
            'Exists and role is judge' => [['user_id' => 1]],
        ];
    }

    public function nameInputValidationProvider()
    {
        return [
            'Required' => [['name' => '']],
            'String' => [['name' => 1]],
            'Maximum of 255 characters' => [['name' => Str::random(256)]],
            'Unique' => [['name' => 'Existing Name']],
        ];
    }

    /**
     * @dataProvider userIdInputValidationProvider
     * @dataProvider nameInputValidationProvider
     *
     * @param mixed $formData
     *
     * @test */
    public function createJudgeFormValidation($formData)
    {
        // Arrange
        $this->seed();

        $this->login(User::whereIs('admin')->first());

        $judge = factory(Judge::class)->create();

        $judge->user->update(['name' => 'Existing Name']);

        $contest = $judge->contest;

        // Act
        $response = $this->post(route('admin.contests.judges.store', ['contest' => $contest->id]), $formData);

        // Assert
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(array_keys($formData)[0]);
    }

    /**
     * @dataProvider nameInputValidationProvider
     *
     * @param mixed $formData
     *
     * @test */
    public function updateJudgeFormValidation($formData)
    {
        // Arrange
        $this->seed();

        $this->login(User::whereIs('admin')->first());

        $judge = factory(Judge::class)->create();

        $judge->user->update(['name' => 'Existing Name']);

        $judge = $judge->contest->judges()->create(factory(Judge::class)->make()->toArray());

        // Act
        $response = $this->patch(route('admin.contests.judges.update', ['contest' => $judge->contest_id, 'judge' => $judge->id]), $formData);

        // Assert
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(array_keys($formData)[0]);
    }

    /** @test */
    public function cannotDeleteJudgeIfAddedInCategories()
    {
        // Arrange
        $this->seed();

        $this->login();

        $judge = factory(Judge::class)->create();

        $category = $judge->contest->categories()->create(factory(Category::class)->make()->toArray());

        $category->categoryJudges()->create([
            'judge_id' => $judge->id,
        ]);

        // Act
        $response = $this->delete(route('admin.contests.judges.destroy', ['contest' => $judge->contest_id, 'judge' => $judge->id]));

        // Assert
        $response
            ->assertRedirect(route('admin.contests.show', ['contest' => $judge->contest_id, 'activeTab' => 'Judges']))
            ->assertSessionHas('error');
    }

    /**
     * @dataProvider getMethodRouteProvider
     *
     * @param mixed $method
     * @param mixed $route
     * @param mixed $parameter
     *
     * @test */
    public function canAccessJudges($method, $route, $parameter = [])
    {
        // Arrange
        $this->seed();

        $this->login();

        $judge = factory(Judge::class)->create();

        // Act
        $response = $this->$method(route($route, $parameter));

        // Assert
        if ('admin.contests.judges.login' === $route) {
            $response->assertRedirect(route('judge.categories.index'));
        } else {
            $response->assertOk();
        }
    }

    /** @test */
    public function canCreateJudgeWithNewUser()
    {
        // Arrange
        $this->seed();

        $this->login(User::whereIs('admin')->first());

        $contest = factory(Contest::class)->create();

        $data = collect(factory(User::class)->make())->only(['name'])->all();

        // Act
        $response = $this->post(route('admin.contests.judges.store', ['contest' => $contest->id]), $data);

        // Assert
        $response
            ->assertRedirect(route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Judges']))
            ->assertSessionHas('success');
    }

    /** @test */
    public function canCreateJudgeWithExistingUser()
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = factory(Contest::class)->create();

        $data = ['user_id' => factory(User::class)->states('judge')->create()->id];

        // Act
        $response = $this->post(route('admin.contests.judges.store', ['contest' => $contest->id]), $data);

        // Assert
        $response
            ->assertRedirect(route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Judges']))
            ->assertSessionHas('success');
    }

    /** @test */
    public function canUpdateJudgeWithNewUser()
    {
        // Arrange
        $this->seed();

        $this->login(User::whereIs('admin')->first());

        $judge = factory(Judge::class)->create();

        $data = collect(factory(User::class)->make())->only(['name'])->all();

        // Act
        $response = $this->patch(route('admin.contests.judges.update', ['contest' => $judge->contest_id, 'judge' => $judge->id]), $data);

        // Assert
        $response
            ->assertRedirect(route('admin.contests.show', ['contest' => $judge->contest_id, 'activeTab' => 'Judges']))
            ->assertSessionHas('success');
    }

    /** @test */
    public function canUpdateJudgeWithExistingUser()
    {
        // Arrange
        $this->seed();

        $this->login();

        $judge = factory(Judge::class)->create();

        $data = ['user_id' => factory(User::class)->states('judge')->create()->id];

        // Act
        $response = $this->post(route('admin.contests.judges.store', ['contest' => $judge->contest_id]), $data);

        // Assert
        $response
            ->assertRedirect(route('admin.contests.show', ['contest' => $judge->contest_id, 'activeTab' => 'Judges']))
            ->assertSessionHas('success');
    }

    /** @test */
    public function canDeleteJudge()
    {
        // Arrange
        $this->seed();

        $this->login();

        $judge = factory(Judge::class)->create();

        // Act
        $response = $this->delete(route('admin.contests.judges.destroy', ['contest' => $judge->contest_id, 'judge' => $judge->id]));

        // Assert
        $response
            ->assertRedirect(route('admin.contests.show', ['contest' => $judge->contest_id, 'activeTab' => 'Judges']))
            ->assertSessionHas('success');
    }
}
