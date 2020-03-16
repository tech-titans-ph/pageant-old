<?php

namespace Tests\Feature\Admin;

use App\Category;
use App\Contest;
use App\Criteria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CriteriaTest extends TestCase
{
    use RefreshDatabase;

    public function allRouteProvider()
    {
        return [
            'Select' => ['get', 'admin.criterias.index'],
            'Store' => ['post', 'admin.contests.categories.criterias.store', ['contest' => 1, 'category' => 1]],
            'Edit' => ['get', 'admin.contests.categories.criterias.edit', ['contest' => 1, 'category' => 1, 'criteria' => 1]],
            'Update' => ['patch', 'admin.contests.categories.criterias.update', ['contest' => 1, 'category' => 1, 'criteria' => 1]],
            'Destroy' => ['delete', 'admin.contests.categories.criterias.destroy', ['contest' => 1, 'category' => 1, 'criteria' => 1]],
        ];
    }

    public function contestAndCategoryParameterRouteProvider()
    {
        return [
            'Store' => ['post', 'admin.contests.categories.criterias.store', ['contest' => 1, 'category' => 1]],
            'Edit' => ['get', 'admin.contests.categories.criterias.edit', ['contest' => 1, 'category' => 1, 'criteria' => 1]],
            'Update' => ['patch', 'admin.contests.categories.criterias.update', ['contest' => 1, 'category' => 1, 'criteria' => 1]],
            'Destroy' => ['delete', 'admin.contests.categories.criterias.destroy', ['contest' => 1, 'category' => 1, 'criteria' => 1]],
        ];
    }

    public function criteriaParameterRouteProvider()
    {
        return [
            'Edit' => ['get', 'admin.contests.categories.criterias.edit', ['contest' => 1, 'category' => 1, 'criteria' => 1]],
            'Update' => ['patch', 'admin.contests.categories.criterias.update', ['contest' => 1, 'category' => 1, 'criteria' => 1]],
            'Destroy' => ['delete', 'admin.contests.categories.criterias.destroy', ['contest' => 1, 'category' => 1, 'criteria' => 1]],
        ];
    }

    public function getMethodRouteProvider()
    {
        return [
            'Select' => ['get', 'admin.criterias.index'],
            'Edit' => ['get', 'admin.contests.categories.criterias.edit', ['contest' => 1, 'category' => 1, 'criteria' => 1]],
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
     * @dataProvider criteriaParameterRouteProvider
     *
     * @param mixed $method
     * @param mixed $route
     * @param mixed $parameter
     *
     * @test */
    public function notExistingCriteriaValidation($method, $route, $parameter = [])
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
    public function invalidRoleProvider($method, $route, $parameter = [])
    {
        // Arrange
        $this->seed();

        $this->login(null, 'judge');

        factory(Criteria::class)->create();

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
            'Unique' => [['name' => 'Existing Criteria']],
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

    /** @test */
    public function cannotCreateCriteriaIfCategoryIsFinishedScoring()
    {
        // Arrange
        $this->seed();

        $this->login();

        $category = factory(Category::class)->create(['status' => 'done']);

        $data = factory(Criteria::class)->make(['category_id' => null])->toArray();

        unset($data['category_id']);

        // Act
        $response = $this->post(route('admin.contests.categories.criterias.store', ['contest' => $category->contest->id, 'category' => $category->id]), $data);

        // Assert
        $response
            ->assertRedirect(route('admin.contests.categories.show', ['contest' => $category->contest->id, 'category' => $category->id, 'activeTab' => 'Criterias']))
            ->assertSessionHas('error');
    }

    /**
     * @dataProvider nameInputValidationProvider
     * @dataProvider percentageInputValidationProvider
     *
     * @param mixed $formData
     *
     * @test */
    public function createCriteriaFormValidation($formData)
    {
        // Arrange
        $this->seed();

        $this->login();

        factory(Criteria::class)->create(['name' => 'Existing Criteria']);

        // Act
        $response = $this->post(route('admin.contests.categories.criterias.store', ['contest' => 1, 'category' => 1]), $formData);

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
     *
     * @test */
    public function updateCriteriaFormValidation($formData)
    {
        // Arrange
        $this->seed();

        $this->login();

        $criteria = factory(Criteria::class)->create(['name' => 'Existing Criteria']);

        $criteria = factory(Criteria::class)->create(['category_id' => $criteria->category->id]);

        // Act
        $response = $this->patch(route('admin.contests.categories.criterias.update', ['contest' => $criteria->category->contest->id, 'category' => $criteria->category->id, 'criteria' => $criteria->id]), $formData);

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
    public function canAccessCriterias($method, $route, $parameter = [])
    {
        // Arrange
        $this->seed();

        $this->login();

        factory(Criteria::class)->create();

        // Act
        $response = $this->$method(route($route, $parameter));

        // Assert
        $response->assertOk();
    }

    /** @test */
    public function canCreateCriteria()
    {
        // Arrange
        $this->seed();

        $this->login();

        $category = factory(Category::class)->create();

        $data = factory(Criteria::class)->make(['category_id' => null])->toArray();

        unset($data['category_id']);

        // Act
        $response = $this->post(route('admin.contests.categories.criterias.store', ['contest' => $category->contest->id, 'category' => $category->id]), $data);

        // Assert
        $response
            ->assertRedirect(route('admin.contests.categories.show', ['contest' => $category->contest->id, 'category' => $category->id, 'activeTab' => 'Criterias']))
            ->assertSessionHas('success');
    }

    /** @test */
    public function canUpdateCriteria()
    {
        // Arrange
        $this->seed();

        $this->login();

        $criteria = factory(Criteria::class)->create();

        $data = factory(Criteria::class)->make(['category_id' => null])->toArray();

        unset($data['category_id']);

        // Act
        $response = $this->patch(route('admin.contests.categories.criterias.update', ['contest' => $criteria->category->contest->id, 'category' => $criteria->category->id, 'criteria' => $criteria->id]), $data);

        // Assert
        $response
            ->assertRedirect(route('admin.contests.categories.show', ['contest' => $criteria->category->contest->id, 'category' => $criteria->category->id, 'activeTab' => 'Criterias']))
            ->assertSessionHas('success');
    }

    /** @test */
    public function canDeleteCriteria()
    {
        // Arrange
        $this->seed();

        $this->login();

        $criteria = factory(Criteria::class)->create();

        // Act
        $response = $this->delete(route('admin.contests.categories.criterias.destroy', ['contest' => $criteria->category->contest->id, 'category' => $criteria->category->id, 'criteria' => $criteria->id]));

        // Assert
        $response
            ->assertRedirect(route('admin.contests.categories.show', ['contest' => $criteria->category->contest->id, 'category' => $criteria->category->id, 'activeTab' => 'Criterias']))
            ->assertSessionHas('success');
    }
}
