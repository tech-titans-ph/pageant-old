<?php

namespace Tests\Feature\Admin;

use App\Category;
use App\Contest;
use App\Contestant;
use App\Judge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ContestTest extends TestCase
{
    use RefreshDatabase;

    public function allRouteProvider()
    {
        return [
            'List' => ['get', 'admin.contests.index'],
            'Item' => ['get', 'admin.contests.show', ['contest' => 1]],
            'Create' => ['get', 'admin.contests.create'],
            'Store' => ['post', 'admin.contests.store'],
            'Edit' => ['get', 'admin.contests.edit', ['contest' => 1]],
            'Update' => ['patch', 'admin.contests.update', ['contest' => 1]],
            'Delete' => ['delete', 'admin.contests.destroy', ['contest' => 1]],
            'Print' => ['get', 'admin.contests.print', ['contest' => 1]],
            'Get Status' => ['get', 'admin.contests.status'],
            'Create Contest from Score' => ['post', 'admin.contests.store-from-score', ['contest' => 1]],
        ];
    }

    public function contestParameterRouteProvider()
    {
        return [
            'Item' => ['get', 'admin.contests.show', ['contest' => 1]],
            'Edit' => ['get', 'admin.contests.edit', ['contest' => 1]],
            'Update' => ['patch', 'admin.contests.update', ['contest' => 1]],
            'Delete' => ['delete', 'admin.contests.destroy', ['contest' => 1]],
            'Print' => ['get', 'admin.contests.print', ['contest' => 1]],
            'Create Contest from Score' => ['post', 'admin.contests.store-from-score', ['contest' => 1]],
        ];
    }

    public function getMethodRouteProvider()
    {
        return [
            // 'List' => ['get', 'admin.contests.index'],
            // 'Item' => ['get', 'admin.contests.show', ['contest' => 1]],
            // 'Create' => ['get', 'admin.contests.create'],
            // 'Edit' => ['get', 'admin.contests.edit', ['contest' => 1]],
            // 'Print' => ['get', 'admin.contests.print', ['contest' => 1]],
            'Get Status' => ['get', 'admin.contests.status'],
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
    public function invalidRoleValidation($method, $route, $parameter = [])
    {
        // Arrange
        $this->seed();

        $this->login(null, 'judge');

        factory(Contest::class)->create();

        // Act
        $response = $this->$method(route($route, $parameter));

        // Assert
        $response->assertStatus(403);
    }

    /**
     * @dataProvider contestParameterRouteProvider
     *
     * @param mixed $method
     * @param mixed $route
     * @param mixed $parameter
     *
     * @test */
    public function notExistingContestValidation($method, $route, $parameter)
    {
        // Arrange
        $this->seed();

        $this->login();

        // Act
        $response = $this->$method(route($route, $parameter));

        // Assert
        $response->assertStatus(404);
    }

    public function nameInputValidationProvider()
    {
        return [
            'Required' => [['name' => '']],
            'String' => [['name' => 0]],
            'Maximum of 255 characters' => [['name' => Str::random(256)]],
        ];
    }

    public function descriptionInputValidationProvider()
    {
        return [
            'Required' => [['description' => '']],
            'String' => [['description' => 0]],
            'Maximum of 255 characters' => [['description' => Str::random(256)]],
        ];
    }

    public function logoInputValidationProvider()
    {
        return [
            'Required' => [['logo' => '']],
            'File' => [['logo' => 'not-a-file']],
            'Image' => [['logo' => UploadedFile::fake()->create('not-image.txt', 8)]],
        ];
    }

    public function updateLogoInputValidationProvider()
    {
        return [
            'File' => [['logo' => 'not-a-file']],
            'Image' => [['logo' => UploadedFile::fake()->create('not-image.txt', 8)]],
        ];
    }

    /**
     * @dataProvider nameInputValidationProvider
     * @dataProvider descriptionInputValidationProvider
     * @dataProvider logoInputValidationProvider
     *
     * @param mixed $formData
     *
     * @test */
    public function createContestFormValidation($formData)
    {
        // Arrange
        $this->seed();

        $this->login();

        // Act
        $response = $this->post(route('admin.contests.store'), $formData);

        // Assert
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(array_keys($formData)[0]);
    }

    /**
     * @dataProvider nameInputValidationProvider
     * @dataProvider descriptionInputValidationProvider
     * @dataProvider updateLogoInputValidationProvider
     *
     * @param mixed $formData
     *
     * @test */
    public function updateContestFormValidation($formData)
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = factory(Contest::class)->create();

        // Act
        $response = $this->patch(route('admin.contests.update', ['contest' => $contest->id]), $formData);

        // Assert
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(array_keys($formData)[0]);
    }

    /** @test */
    public function cannotDeleteContestIfHasContestants()
    {
        // Arrange
        $this->seed();

        $this->login();

        $contestant = factory(Contestant::class)->create();

        // Act
        $response = $this->delete(route('admin.contests.destroy', ['contest' => $contestant->contest->id]));

        // Assert
        $response
            ->assertRedirect(route('admin.contests.show', ['contest' => $contestant->contest->id]))
            ->assertSessionHas('error');
    }

    /** @test */
    public function cannotDeleteContestIfHasJudges()
    {
        // Arrange
        $this->seed();

        $this->login();

        $judge = factory(Judge::class)->create();

        // Act
        $response = $this->delete(route('admin.contests.destroy', ['contest' => $judge->contest->id]));

        // Assert
        $response
            ->assertRedirect(route('admin.contests.show', ['contest' => $judge->contest->id]))
            ->assertSessionHas('error');
    }

    /** @test */
    public function cannotDeleteIfHasCategories()
    {
        // Arrange
        $this->seed();

        $this->login();

        $category = factory(Category::class)->create();

        // Act
        $response = $this->delete(route('admin.contests.destroy', ['contest' => $category->contest->id]));

        // Assert
        $response
            ->assertRedirect(route('admin.contests.show', ['contest' => $category->contest->id]))
            ->assertSessionHas('error');
    }

    public function notFinishedCategoryStatusProvider()
    {
        return [
            'Default' => [['status' => 'que']],
            'Scoring' => [['status' => 'scoring']],
        ];
    }

    /**
     * @dataProvider notFinishedCategoryStatusProvider
     *
     * @param mixed $status
     *
     * @test */
    public function cannotPrintIfACategoryIsNotFinishedScoring($status)
    {
        // Arrange
        $this->seed();

        $this->login();

        $category = factory(Category::class)->create($status);

        // Act
        $response = $this->get(route('admin.contests.print', ['contest' => $category->contest->id]));

        // Assert
        $response->assertStatus(403);
    }

    /**
     * @dataProvider notFinishedCategoryStatusProvider
     *
     * @param mixed $status
     *
     * @test */
    public function cannotCreateContestFromScoreIfAllCategoriesInContestIsNotFinishedScoring($status)
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = $this->contestFaker(1, 1, 1, 1, $status['status']);

        $data = factory(Contest::class)->make([
            'logo' => UploadedFile::fake()->image('image.png', 8),
            'contestant_count' => 1,
        ])->toArray();

        // Act
        $response = $this->post(route('admin.contests.store-from-score', ['contest' => $contest->id]), $data);

        // Assert
        $response
            ->assertRedirect(route('admin.contests.show', ['contest' => $contest->id]))
            ->assertSessionHas('error');
    }

    public function contestantCountInputValidationProvider()
    {
        return [
            'Required' => [['contestant_count' => '']],
            'Integer' => [['contestant_count' => 1.5]],
            'Minimum of 1' => [['contestant_count' => 0]],
            'Maximum of number of contestants' => [['contestant_count' => 11]],
        ];
    }

    /**
     * @dataProvider nameInputValidationProvider
     * @dataProvider descriptionInputValidationProvider
     * @dataProvider logoInputValidationProvider
     * @dataProvider contestantCountInputValidationProvider
     *
     * @param mixed $formData
     *
     * @test */
    public function createContestFromScoreFormValidation($formData)
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = $this->contestFaker(1, 10, 1, 1, 'done');

        // Act
        $response = $this->post(route('admin.contests.store-from-score', ['contest' => $contest->id]), $formData);

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
    public function canAccessContests($method, $route, $parameter = [])
    {
        // Arrange
        $this->seed();

        $this->login();

        if ($parameter) {
            $this->contestFaker(1, 1, 1, 1, 'done');
        }

        // Act
        $response = $this->$method(route($route, $parameter));

        // Assert
        $response->assertOk();
    }

    /** @test */
    public function canCreateContest()
    {
        // Arrange
        $this->seed();

        $this->login();

        $data = factory(Contest::class)->make([
            'logo' => UploadedFile::fake()->image('image.png', 8),
        ])->toArray();

        // Act
        $response = $this->post(route('admin.contests.store'), $data);

        // Assert
        $response
            ->assertRedirect(route('admin.contests.show', ['contest' => 1]))
            ->assertSessionHas('success');
    }

    /** @test */
    public function canUpdateContest()
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = factory(Contest::class)->create();

        $data = factory(Contest::class)->make([
            'logo' => UploadedFile::fake()->image('image.png', 8),
        ])->toArray();

        // Act
        $response = $this->patch(route('admin.contests.update', ['contest' => $contest->id]), $data);

        // Assert
        $response
            ->assertRedirect(route('admin.contests.show', ['contest' => $contest->id]))
            ->assertSessionHas('success');

        Storage::assertMissing($contest->logo);
    }

    /** @test */
    public function canDeleteContest()
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = factory(Contest::class)->create();

        // Act
        $response = $this->delete(route('admin.contests.destroy', ['contest' => $contest->id]));

        // Assert
        $response
            ->assertRedirect(route('admin.contests.index'))
            ->assertSessionHas('success');
    }

    /** @test */
    public function canCreateContestFromScore()
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = $this->contestFaker(1, 1, 1, 1, 'done');

        $data = factory(Contest::class)->make([
            'logo' => UploadedFile::fake()->image('image.png', 8),
            'contestant_count' => 1,
            'include_judges' => 1,
        ])->toArray();

        // Act
        $response = $this->post(route('admin.contests.store-from-score', ['contest' => $contest->id]), $data);

        // Assert
        $response
            ->assertRedirect(route('admin.contests.show', ['contest' => 2]))
            ->assertSessionHas('success');
    }
}
