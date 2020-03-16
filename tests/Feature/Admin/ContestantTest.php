<?php

namespace Tests\Feature\Admin;

use App\Category;
use App\CategoryContestant;
use App\Contest;
use App\Contestant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ContestantTest extends TestCase
{
    use RefreshDatabase;

    public function allRouteProvider()
    {
        return [
            'Item' => ['get', 'admin.contests.contestants.show', ['contest' => 1, 'contestant' => 1]],
            'Create' => ['get', 'admin.contests.contestants.create', ['contest' => 1]],
            'Store' => ['post', 'admin.contests.contestants.store', ['contest' => 1]],
            'Update' => ['patch', 'admin.contests.contestants.update', ['contest' => 1, 'contestant' => 1]],
            'Delete' => ['delete', 'admin.contests.contestants.destroy', ['contest' => 1, 'contestant' => 1]],
        ];
    }

    public function contestantParameterRouteProvider()
    {
        return [
            'Item' => ['get', 'admin.contests.contestants.show', ['contest' => 1, 'contestant' => 1]],
            'Update' => ['patch', 'admin.contests.contestants.update', ['contest' => 1, 'contestant' => 1]],
            'Delete' => ['delete', 'admin.contests.contestants.destroy', ['contest' => 1, 'contestant' => 1]],
        ];
    }

    public function getMethodRouteProvider()
    {
        return [
            'Item' => ['get', 'admin.contests.contestants.show', ['contest' => 1, 'contestant' => 1]],
            'Create' => ['get', 'admin.contests.contestants.create', ['contest' => 1]],
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
     * @dataProvider contestantParameterRouteProvider
     *
     * @param mixed $method
     * @param mixed $route
     * @param mixed $parameter
     *
     * @test */
    public function notExistingContestantValidation($method, $route, $parameter)
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = factory(Contest::class)->create();

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

        $contestant = factory(Contestant::class)->create();

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
    public function cannotAccessContestantScoreIfACategoryIsNotFinishedScoring($status)
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = $this->contestFaker(1, 1, 1, 1, $status);

        // Act
        $response = $this->get(route('admin.contests.contestants.show', ['contest' => $contest->id, 'contestant' => $contest->contestants()->first()->id]));

        // Assert
        $response->assertStatus(403);
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

    public function descriptionInputValidationProvider()
    {
        return [
            'Required' => [['description' => '']],
            'String' => [['description' => 1]],
            'Maximum of 255 characters' => [['description' => Str::random(256)]],
        ];
    }

    public function numberInputValidationProvider()
    {
        return [
            'Required' => [['number' => '']],
            'Integer' => [['number' => 1.5]],
            'Minimum of 1' => [['number' => 0]],
            'Maximum of 255' => [['number' => 256]],
            'Unique' => [['number' => 1]],
        ];
    }

    public function pictureInputValidationProvider()
    {
        return [
            'Required' => [['picture' => '']],
            'File' => [['picture' => 'not-file']],
            'Image' => [['picture' => UploadedFile::fake('not-image.txt')]],
        ];
    }

    public function editPictureInputValidationProvider()
    {
        return [
            'File' => [['picture' => 'not-file']],
            'Image' => [['picture' => UploadedFile::fake('not-image.txt')]],
        ];
    }

    /**
     * @dataProvider nameInputValidationProvider
     * @dataProvider descriptionInputValidationProvider
     * @dataProvider numberInputValidationProvider
     * @dataProvider pictureInputValidationProvider
     *
     * @param mixed $formData
     *
     * @test */
    public function createContestantFormValidation($formData)
    {
        // Arrange
        $this->seed();

        $this->login();

        $contestant = factory(Contestant::class)->create(['number' => 1, 'name' => 'Existing Name']);

        // Act
        $response = $this->post(route('admin.contests.contestants.store', ['contest' => $contestant->contest_id]), $formData);

        // dd($response->getContent());

        // Assert
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(array_keys($formData)[0]);
    }

    /**
     * @dataProvider nameInputValidationProvider
     * @dataProvider descriptionInputValidationProvider
     * @dataProvider numberInputValidationProvider
     * @dataProvider editPictureInputValidationProvider
     *
     * @param mixed $formData
     *
     * @test */
    public function updateContestantFormValidation($formData)
    {
        // Arrange
        $this->seed();

        $this->login();

        $contestant = factory(Contestant::class)->create(['number' => 1, 'name' => 'Existing Name']);

        $contestant = $contestant->contest->contestants()->create(
            factory(Contestant::class)->make(['contest_id' => $contestant->contest_id])->toArray()
        );

        // Act
        $response = $this->patch(route('admin.contests.contestants.update', ['contest' => $contestant->contest_id, 'contestant' => $contestant->id]), $formData);

        // Assert
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(array_keys($formData)[0]);
    }

    /** @test */
    public function cannotDeleteContestantIfAddedInCategories()
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = factory(Contest::class)->create();

        $contestant = factory(Contestant::class)->create(['contest_id' => $contest->id]);

        $category = factory(Category::class)->create(['contest_id' => $contest->id]);

        CategoryContestant::create(['category_id' => $category->id, 'contestant_id' => $contestant->id]);

        // Act
        $response = $this->delete(route('admin.contests.contestants.destroy', ['contest' => $contest->id, 'contestant' => $contestant->id]));

        // Assert
        $response
            ->assertRedirect(route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Contestants']))
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
    public function canAccessContestants($method, $route, $parameter = [])
    {
        // Arrange
        $this->seed();

        $this->login();

        factory(Contestant::class)->create();

        // Act
        $response = $this->$method(route($route, $parameter));

        // Assert
        $response->assertOk();
    }

    /** @test */
    public function canCreateContestant()
    {
        // Arrange
        $this->seed();

        $this->login();

        $contest = factory(Contest::class)->create();

        $data = factory(Contestant::class)->make([
            'contest_id' => null,
            'picture' => UploadedFile::fake()->image('picture.png'),
        ])->toArray();

        // Act
        $response = $this->post(route('admin.contests.contestants.store', ['contest' => $contest->id]), $data);

        // Assert
        $response
            ->assertRedirect(route('admin.contests.contestants.create', ['contest' => $contest->id, 'activeTab' => 'Contestants']))
            ->assertSessionHas('success');
    }

    /** @test */
    public function canUpdateContestant()
    {
        // Arrange
        $this->seed();

        $this->login();

        $contestant = factory(Contestant::class)->create();

        $data = factory(Contestant::class)->make([
            'contest_id' => null,
            'picture' => UploadedFile::fake()->image('picture.png'),
        ])->toArray();

        // Act
        $response = $this->patch(route('admin.contests.contestants.update', ['contest' => $contestant->contest_id, 'contestant' => $contestant->id]), $data);

        // Assert
        $response
            ->assertRedirect(route('admin.contests.show', ['contest' => $contestant->contest_id, 'activeTab' => 'Contestants']))
            ->assertSessionHas('success');

        Storage::assertMissing($contestant->picture);
    }

    /** @test */
    public function canDeleteContestant()
    {
        // Arrange
        $this->seed();

        $this->login();

        $contestant = factory(Contestant::class)->create();

        // Act
        $response = $this->delete(route('admin.contests.contestants.destroy', ['contest' => $contestant->contest_id, 'contestant' => $contestant->id]));

        // Assert
        $response
            ->assertRedirect(route('admin.contests.show', ['contest' => $contestant->contest_id, 'activeTab' => 'Contestants']))
            ->assertSessionHas('success');
    }
}
