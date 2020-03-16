<?php

namespace Tests\Feature\Admin;

use App\Category;
use App\Contest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function allRouteProvider()
    {
        return [
            'Select' => ['get', 'admin.categories.index'],
            'Item' => ['get', 'admin.contests.categories.show', ['contest' => 1, 'category' => 1]],
            'Store' => ['post', 'admin.contests.categories.store', ['contest' => 1]],
            'Update' => ['patch', 'admin.contests.categories.update', ['contest' => 1, 'category' => 1]],
            'Delete' => ['delete', 'admin.contests.categories.destroy', ['contest' => 1, 'category' => 1]],
            'Start Score' => ['patch', 'admin.contests.categories.start', ['contest' => 1, 'category' => 1]],
            'Finish Score' => ['patch', 'admin.contests.categories.finish', ['contest' => 1, 'category' => 1]],
            'Print Score' => ['get', 'admin.contests.categories.print', ['contest' => 1, 'category' => 1]],
            'Store from Score' => ['post', 'admin.contests.categories.store-from-score', ['contest' => 1, 'category' => 1]],
        ];
    }

    public function contestParameterRouteProvider()
    {
        return [
            'Item' => ['get', 'admin.contests.categories.show', ['contest' => 1, 'category' => 1]],
            'Store' => ['post', 'admin.contests.categories.store', ['contest' => 1]],
            'Update' => ['patch', 'admin.contests.categories.update', ['contest' => 1, 'category' => 1]],
            'Delete' => ['delete', 'admin.contests.categories.destroy', ['contest' => 1, 'category' => 1]],
            'Start Score' => ['patch', 'admin.contests.categories.start', ['contest' => 1, 'category' => 1]],
            'Finish Score' => ['patch', 'admin.contests.categories.finish', ['contest' => 1, 'category' => 1]],
            'Print Score' => ['get', 'admin.contests.categories.print', ['contest' => 1, 'category' => 1]],
            'Store from Score' => ['post', 'admin.contests.categories.store-from-score', ['contest' => 1, 'category' => 1]],
        ];
    }

    public function categoryParameterRouteProvider()
    {
        return [
            'Item' => ['get', 'admin.contests.categories.show', ['contest' => 1, 'category' => 1]],
            'Update' => ['patch', 'admin.contests.categories.update', ['contest' => 1, 'category' => 1]],
            'Delete' => ['delete', 'admin.contests.categories.destroy', ['contest' => 1, 'category' => 1]],
            'Start Score' => ['patch', 'admin.contests.categories.start', ['contest' => 1, 'category' => 1]],
            'Finish Score' => ['patch', 'admin.contests.categories.finish', ['contest' => 1, 'category' => 1]],
            'Print Score' => ['get', 'admin.contests.categories.print', ['contest' => 1, 'category' => 1]],
            'Store from Score' => ['post', 'admin.contests.categories.store-from-score', ['contest' => 1, 'category' => 1]],
        ];
    }

    public function getMethodRouteProvider()
    {
        return [
            'Item' => ['get', 'admin.contests.categories.show', ['contest' => 1, 'category' => 1]],
            'Print Score' => ['get', 'admin.contests.categories.print', ['contest' => 1, 'category' => 1]],
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
     * @dataProvider contestParameterRouteProvider
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

        $this->login();

        factory(Contest::class)->create();

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

        factory(Category::class)->create();

        // Act
        $response = $this->$method(route($route, $parameter));

        // Assert
        $response->assertStatus(403);
    }

    public function nameInputValidationProvider()
    {
        return [
            'Required' => [['name' => '']],
            'String' => [['name' => 1]],
            'Maximum of 255 characters' => [['name' => Str::random(256)]],
            'Unique' => [['name' => 'Existing Category']],
        ];
    }

    public function percentageInputValidationProvider()
    {
        return [
            'Required' => [['percentage' => '']],
            'Integer' => [['percentage' => 1.5]],
            'Minimum of 1' => [['percentage' => 0]],
            'Maximum of 100' => [['percentage' => 101]],
        ];
    }

    /**
     * @dataProvider nameInputValidationProvider
     * @dataProvider percentageInputValidationProvider
     *
     * @param mixed $formData
     *
     * @test */
    public function createCategoryFormValidation($formData)
    {
        // Arrange
        $this->seed();

        $this->login();

        $category = factory(Category::class)->create(['name' => 'Existing Category']);

        // Act
        $response = $this->post(route('admin.contests.categories.store', ['contest' => $category->contest->id]), $formData);

        // Assert
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(array_keys($formData)[0]);
    }

    /**
     * @dataProvider nameInputValidationProvider
     * @dataProvider percentageInputValidationProvider
     *
     * @param mixed $formData
     * @test */
    public function updateCategoryFormValidation($formData)
    {
        // Arrange
        $this->seed();

        $this->login();

        $category = factory(Category::class)->create(['name' => 'Existing Category']);

        $category = factory(Category::class)->create(['contest_id' => $category->contest->id]);

        // Act
        $response = $this->patch(route('admin.contests.categories.update', ['contest' => $category->contest->id, 'category' => $category->id]), $formData);

        // Assert
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(array_keys($formData)[0]);
    }

    public function deleteCategoryValidationProvider()
    {
        return [
            'Has Criterias' => [1, 0, 0],
            'Has Category Judge' => [0, 1, 0],
            'Has Category Contestant' => [0, 0, 1],
        ];
    }

    /**
     * @dataProvider deleteCategoryValidationProvider
     *
     * @param mixed $criteriaCount
     * @param mixed $categoryJudgeCount
     * @param mixed $categoryContestantCount
     *
     * @test */
    public function deleteCategoryValidation($criteriaCount, $categoryJudgeCount, $categoryContestantCount)
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = $this->contestFactory($categoryJudgeCount, $categoryContestantCount, 1, $criteriaCount);

        $category = $contest->categories()->first();

        $pages = [
            ['page' => 'contest', 'redirect' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Categories'])],
            ['page' => 'category', 'redirect' => route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id])],
        ];

        foreach ($pages as $page) {
            // Act
            $response = $this->delete(route('admin.contests.categories.destroy', ['contest' => $contest->id, 'category' => $contest->categories()->first()->id, 'redirect' => $page['page']]));

            // Assert
            $response
                ->assertRedirect($page['redirect'])
                ->assertSessionHas('error');
        }
    }

    /** @test */
    public function cannotStartScoreIfOtherCategoryHasStartedScoring()
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = $this->contestFaker();

        $category = $contest->categories()->first();

        factory(Category::class)->create(['contest_id' => $contest->id, 'status' => 'scoring']);

        $pages = [
            ['page' => 'contest', 'redirect' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Categories'])],
            ['page' => 'category', 'redirect' => route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id])],
        ];

        foreach ($pages as $page) {
            // Act
            $response = $this->patch(route('admin.contests.categories.start', ['contest' => $contest->id, 'category' => $category->id, 'redirect' => $page['page']]));

            // Assert
            $response
                ->assertRedirect($page['redirect'])
                ->assertSessionHas('error');
        }
    }

    public function startScoreValidationProvider()
    {
        return [
            'No Criteria' => [0, 1, 1],
            'No Category Judge' => [1, 0, 1],
            'No Category Contestant' => [1, 1, 0],
        ];
    }

    /**
     * @dataProvider startScoreValidationProvider
     *
     * @param mixed $criteriaCount
     * @param mixed $categoryJudgeCount
     * @param mixed $categoryContestantCount
     *
     * @test */
    public function startScoreValidation($criteriaCount, $categoryJudgeCount, $categoryContestantCount)
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = $this->contestFactory($categoryJudgeCount, $categoryContestantCount, 1, $criteriaCount);

        $category = $contest->categories()->first();

        $pages = [
            ['page' => 'contest', 'redirect' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Categories'])],
            ['page' => 'category', 'redirect' => route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id])],
        ];

        foreach ($pages as $page) {
            // Act
            $response = $this->patch(route('admin.contests.categories.start', ['contest' => $contest->id, 'category' => $category->id, 'redirect' => $page['page']]));

            // Assert
            $response
                ->assertRedirect($page['redirect'])
                ->assertSessionHas('error');
        }
    }

    /** @test */
    public function cannotStartScoreIfCategoryHasStartedScoring()
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = $this->contestFactory();

        $category = $contest->categories()->first();

        $category->update(['status' => 'scoring']);

        $pages = [
            ['page' => 'contest', 'redirect' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Categories'])],
            ['page' => 'category', 'redirect' => route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id])],
        ];

        foreach ($pages as $page) {
            // Act
            $response = $this->patch(route('admin.contests.categories.start', ['contest' => $contest->id, 'category' => $category->id, 'redirect' => $page['page']]));

            // Assert
            $response
                ->assertRedirect($page['redirect'])
                ->assertSessionHas('error');
        }
    }

    public function categoryStatusProvider()
    {
        return [
            'Default' => [['status' => 'que']],
            'Finish' => [['status' => 'done']],
        ];
    }

    public function notFinishCategoryStatusProvider()
    {
        return [
            'Default' => [['status' => 'que']],
            'Scoring' => [['status' => 'scoring']],
        ];
    }

    /**
     * @dataProvider categoryStatusProvider
     *
     * @param mixed $status
     *
     * @test */
    public function cannotFinishScoreIfCategoryHasNotStartedScoring($status)
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = $this->contestFactory();

        $category = $contest->categories()->first();

        $category->update($status);

        $pages = [
            ['page' => 'contest', 'redirect' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Categories'])],
            ['page' => 'category', 'redirect' => route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id])],
        ];

        foreach ($pages as $page) {
            // Act
            $response = $this->patch(route('admin.contests.categories.finish', ['contest' => $contest->id, 'category' => $category->id, 'redirect' => $page['page']]));

            // Assert
            $response
                ->assertRedirect($page['redirect'])
                ->assertSessionHas('error');
        }
    }

    /** @test */
    public function cannotFinishScoreIfJudgesHasNotCompletedScoring()
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = $this->contestFaker(1, 1, 1, 1, 'scoring');

        $category = $contest->categories()->first();

        $pages = [
            ['page' => 'contest', 'redirect' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Categories'])],
            ['page' => 'category', 'redirect' => route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id])],
        ];

        foreach ($pages as $page) {
            // Act
            $response = $this->patch(route('admin.contests.categories.finish', ['contest' => $contest->id, 'category' => $category->id, 'redirect' => $page['page']]));

            // Assert
            $response
                ->assertRedirect($page['redirect'])
                ->assertSessionHas('error');
        }
    }

    /**
     * @dataProvider notFinishCategoryStatusProvider
     *
     * @param mixed $status
     *
     * @test */
    public function cannotPrintScoreIfNotFinishedScoring($status)
    {
        // Arrange
        $this->seed();

        $this->login();

        $category = factory(Category::class)->create($status);

        // Act
        $response = $this->get(route('admin.contests.categories.print', ['contest' => $category->contest->id, 'category' => $category->id]));

        // Assert
        $response->assertStatus(403);
    }

    /**
     * @dataProvider notFinishCategoryStatusProvider
     *
     * @param mixed $status
     *
     * @test */
    public function cannotCreateCategoryFromScoreIfCategoryIsNotFinishedScoring($status)
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = $this->contestFaker(1, 1, 1, 1, $status['status']);

        $category = $contest->categories()->first();

        $data = factory(Category::class)->make(['contest_id' => null])->toArray();

        unset($data['contest_id']);

        $data['contestant_count'] = 1;
        $data['include_judges'] = 1;

        // Act
        $response = $this->post(route('admin.contests.categories.store-from-score', ['contest' => $contest->id, 'category' => $category->id]), $data);

        // Assert
        $response
            ->assertRedirect(route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Create Category from Results']))
            ->assertSessionHas('error');
    }

    public function contestantCountInputValidationProvider()
    {
        return [
            'Required' => [['contestant_count' => '']],
            'Integer' => [['contestant_count' => 1.5]],
            'Minimum of 1' => [['contestant_count' => 0]],
            'Maximum of percentage from category' => [['contestant_count' => 11]],
        ];
    }

    /**
     * @dataProvider nameInputValidationProvider
     * @dataProvider percentageInputValidationProvider
     * @dataProvider contestantCountInputValidationProvider
     *
     * @param mixed $formData
     *
     * @test */
    public function createCategoryFromScoreFormValidation($formData)
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = $this->contestFaker(1, 1, 1, 1, 'done');

        factory(Category::class)->create(['contest_id' => $contest->id, 'name' => 'Existing Category']);

        $category = $contest->categories()->first();

        $category->update(['percentage' => 10]);

        // Act
        $response = $this->post(route('admin.contests.categories.store-from-score', ['contest' => $contest->id, 'category' => $category->id]), $formData);

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
    public function canAccessCategories($method, $route, $parameter = [])
    {
        // Arrange
        $this->seed();

        $this->login();

        $this->contestFaker(1, 1, 1, 1, 'done');

        // Act
        $response = $this->$method(route($route, $parameter));

        // Assert
        $response->assertOk();
    }

    /** @test */
    public function canCreateCategory()
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = factory(Contest::class)->create();

        $data = factory(Category::class)->make(['contest_id' => null])->toArray();

        unset($data['contest_id']);

        // Act
        $response = $this->post(route('admin.contests.categories.store', ['contest' => $contest->id]), $data);

        // Assert
        $response
            ->assertRedirect(route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => 1]))
            ->assertSessionHas('success');
    }

    /** @test */
    public function canUpdateCategory()
    {
        // Arrange
        $this->seed();

        $this->login();

        $category = factory(Category::class)->create();

        $data = factory(Category::class)->make(['contest_id' => null])->toArray();

        unset($data['contest_id']);

        // Act
        $response = $this->patch(route('admin.contests.categories.update', ['contest' => $category->contest->id, 'category' => $category->id]), $data);

        // Assert
        $response
            ->assertRedirect(route('admin.contests.categories.show', ['contest' => $category->contest->id, 'category' => $category->id]) . '?')
            ->assertSessionHas('success');
    }

    public function categoryRedirectPageProvider()
    {
        return [
            'Contest' => ['contest', 'admin.contests.show', ['contest' => 1, 'activeTab' => 'Categories']],
            'Category' => ['category', 'admin.contests.categories.show', ['contest' => 1, 'category' => 1]],
        ];
    }

    /** @test */
    public function canDeleteCategory()
    {
        // Arrange
        $this->seed();

        $this->login();

        factory(Category::class)->create();

        // Act
        $response = $this->delete(route('admin.contests.categories.destroy', ['contest' => 1, 'category' => 1]));

        // Assert
        $response
            ->assertRedirect(route('admin.contests.show', ['contest' => 1, 'activeTab' => 'Categories']))
            ->assertSessionHas('success');
    }

    /**
     * @dataProvider categoryStatusProvider
     *
     * @param mixed $status
     *
     * @test */
    public function canStartScore($status)
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = $this->contestFactory();

        $category = $contest->categories()->first();

        $pages = [
            ['page' => 'contest', 'redirect' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Categories'])],
            ['page' => 'category', 'redirect' => route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id])],
        ];

        foreach ($pages as $page) {
            $category->update($status);

            // Act
            $response = $this->patch(route('admin.contests.categories.start', ['contest' => $contest->id, 'category' => $category->id, 'redirect' => $page['page']]));

            // Assert
            $response
                ->assertRedirect($page['redirect']);
        }
    }

    /**
     * @dataProvider categoryRedirectPageProvider
     *
     * @param mixed $page
     * @param mixed $redirect
     * @param mixed $parameter
     *
     * @test */
    public function canFinishScore($page, $redirect, $parameter)
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = $this->contestFaker(1, 1, 1, 1, 'scoring');

        $category = $contest->categories()->first();

        $category->categoryJudges()->update(['completed' => 1]);

        // Act
        $response = $this->patch(route('admin.contests.categories.finish', ['contest' => 1, 'category' => 1, 'redirect' => $page]));

        if ('category' === $page) {
            $parameter['activeTab'] = 'Scores';
        }

        // Assert
        $response
            ->assertRedirect(route($redirect, $parameter))
            ->assertSessionHas('success');
    }

    /** @test */
    public function canCreateCategoryFromScore()
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = $this->contestFaker(1, 1, 1, 1, 'done');

        $category = $contest->categories()->first();

        $data = factory(Category::class)->make(['contest_id' => null])->toArray();

        unset($data['contest_id']);

        $data['contestant_count'] = 1;

        $data['include_judges'] = 1;

        // Act
        $response = $this->post(route('admin.contests.categories.store-from-score', ['contest' => $contest->id, 'category' => $category->id]), $data);

        // Assert
        $response
            ->assertRedirect(route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => 2]))
            ->assertSessionHas('success');
    }
}
