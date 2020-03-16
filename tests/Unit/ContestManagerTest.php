<?php

namespace Tests\Unit;

use App\Category;
use App\Contest;
use App\Contestant;
use App\Criteria;
use App\CriteriaScore;
use App\Judge;
use App\Managers\ContestManager;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContestManagerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function canCreate()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $data = factory(Contest::class)->make(['logo' => UploadedFile::fake()->image('logo.png')])->toArray();

        // Act
        $response = $manager->create($data);

        // Assert
        $this->assertDatabaseHas('contests', $response->toArray());

        Storage::assertExists($response->logo);
    }

    /** @test */
    public function canUpdate()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $contest = factory(Contest::class)->create();

        $data = factory(Contest::class)->make(['logo' => UploadedFile::fake()->image('logo.png')])->toArray();

        // Act
        $response = $manager->update($contest, $data);

        // Assert
        $this->assertDatabaseHas('contests', $response->toArray());

        Storage::assertExists($response->logo);
    }

    /** @test */
    public function canDelete()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $contest = factory(Contest::class)->create();

        // Act
        $manager->delete($contest);

        // Assert
        $this->assertDatabaseMissing('contests', $contest->toArray());

        Storage::assertMissing($contest->logo);
    }

    /** @test */
    public function canAddJudgeWithNewUser()
    {
        // Arrange
        $this->seed();

        $manager = new contestManager();

        $category = factory(Category::class)->create();

        $data = collect(factory(User::class)->make())->only(['name'])->all();

        // Act
        $response = $manager->addJudge($category->contest, $data);

        // Assert
        $this->assertTrue($response->user->isA('judge'));

        $this
            ->assertDatabaseHas('users', $response->user->toArray())
            ->assertDatabaseHas('judges', collect($response)->except(['user'])->all());
    }

    /** @test */
    public function canAddJudgeWithExistingUser()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $category = factory(Category::class)->create();

        $user = factory(User::class)->states('judge')->create();

        $data = ['user_id' => $user->id];

        // Act
        $response = $manager->addJudge($category->contest, $data);

        // Assert
        $this->assertDatabaseHas('judges', $response->toArray());
    }

    /** @test */
    public function canEditJudgeWithNewUser()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $judge = factory(Judge::class)->create();

        $data = collect(factory(User::class)->make())->only(['name'])->all();

        // Act
        $response = $manager->editJudge($judge, $data);

        // Assert
        $this
            ->assertDatabaseHas('users', $response->user->toArray())
            ->assertDatabaseHas('judges', collect($response)->except(['user'])->all());
    }

    /** @test */
    public function canEditJudgeWithExistingUser()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $judge = factory(Judge::class)->create();

        $data = ['user_id' => factory(User::class)->states('judge')->create()->id];

        // Act
        $response = $manager->editJudge($judge, $data);

        // Assert
        $this->assertDatabaseHas('judges', $response->toArray());
    }

    /** @test */
    public function canRemoveJudge()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $judge = factory(Judge::class)->create();

        // Act
        $manager->removeJudge($judge);

        // Assert
        $this->assertDatabaseMissing('judges', $judge->toArray());
    }

    /** @test */
    public function canLoginJudge()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $judge = factory(Judge::class)->create();

        // Act
        $manager->loginJudge($judge);

        // Assert
        $this->assertEquals(auth()->user(), $judge->user);

        $this->assertEquals(session('judge'), $judge->id);
    }

    /** @test */
    public function canAddContestant()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $category = factory(Category::class)->create();

        $data = collect(factory(Contestant::class)->make([
            'contest_id' => null,
            'picture' => UploadedFile::fake()->image('picture.png'),
        ]))->except(['contest_id'])->all();

        // Act
        $response = $manager->addContestant($category->contest, $data);

        // Assert
        $this->assertDatabaseHas('contestants', $response->toArray());

        Storage::assertExists($response->picture);
    }

    /** @test */
    public function canEditContestant()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $contestant = factory(Contestant::class)->create();

        $data = collect(factory(Contestant::class)->make([
            'contest_id' => null,
            'picture' => UploadedFile::fake()->image('picture.png'),
        ]))->except(['contest_id'])->all();

        // Act
        $response = $manager->editContestant($contestant, $data);

        // Assert
        $this->assertDatabaseHas('contestants', $response->toArray());

        Storage::assertExists($response->picture);
    }

    /** @test */
    public function canRemoveContestant()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $contestant = factory(Contestant::class)->create();

        // Act
        $manager->removeContestant($contestant);

        // Assert
        $this->assertDatabaseMissing('contestants', $contestant->toArray());

        Storage::assertMissing($contestant->picture);
    }

    /** @test */
    public function canAddCategory()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $contest = factory(Contest::class)->create();

        $contest->judges()->create(
            factory(Judge::class)->make(['contest_id' => $contest->id])->toArray()
        );

        $contest->contestants()->create(
            factory(Contestant::class)->make(['contest_id' => $contest->id])->toArray()
        );

        $data = collect(
            factory(Category::class)->make(['contest_id' => null])->toArray()
        )->except(['contest_id'])->all();

        // Act
        $response = $manager->addCategory($contest, $data);

        // Assert
        $this->assertDatabaseHas('categories', $response->toArray())
            ->assertDatabaseHas('category_judges', $response->categoryJudges()->first()->toArray())
            ->assertDatabaseHas('category_contestants', $response->categoryContestants()->first()->toArray());
    }

    /** @test */
    public function canEditCategory()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $category = factory(Category::class)->create();

        $data = collect(factory(Category::class)->make([
            'contest_id' => null,
        ])->toArray())->except(['contest_id'])->all();

        // Act
        $response = $manager->editCategory($category, $data);

        // Assert
        $this->assertDatabaseHas('categories', $response->toArray());
    }

    /** @test */
    public function canRemoveCategory()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $category = factory(Category::class)->create();

        // Act
        $manager->removeCategory($category);

        // Assert
        $this->assertDatabaseMissing('categories', $category->toArray());
    }

    /** @test */
    public function canStartCategory()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $category = factory(Category::class)->create();

        // Act
        $response = $manager->startCategory($category);

        // Assert
        $this->assertDatabaseHas('categories', $response->toArray());
    }

    /** @test */
    public function canFinishCategory()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $category = factory(Category::class)->create();

        // Act
        $response = $manager->finishCategory($category);

        // Assert
        $this->assertDatabaseHas('categories', $response->toArray());
    }

    /** @test */
    public function canAddCriteria()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $category = factory(Category::class)->create();

        $data = collect(
            factory(Criteria::class)->make(['category_id' => null])->toArray()
        )->except(['category_id'])->all();

        // Act
        $response = $manager->addCriteria($category, $data);

        // Assert
        $this->assertDatabaseHas('criterias', $response->toArray());
    }

    /** @test */
    public function canEditCriteria()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $criteria = factory(Criteria::class)->create();

        $data = collect(
            factory(Criteria::class)->make(['category_id' => null])
        )->except(['category_id'])->all();

        // Act
        $response = $manager->editCriteria($criteria, $data);

        // Assert
        $this->assertDatabaseHas('criterias', $response->toArray());
    }

    /** @test */
    public function canRemoveCriteria()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $contest = $this->contestFactory();

        $category = $contest->categories()->first();

        $categoryJudge = $category->categoryJudges()->first();

        $categoryContestant = $category->categoryContestants()->first();

        $criteria = $category->criterias()->first();

        $manager->loginJudge($categoryJudge->judge()->first());

        $score = $this->faker->numberBetween(1, $criteria->percentage);

        $criteriaScore = $manager->setScore($categoryContestant, $criteria, $score);

        // Act
        $manager->removeCriteria($criteria);

        // Assert
        $this
            ->assertDatabaseMissing('criterias', $criteria->toArray())
            ->assertDatabaseMissing('criteria_scores', $criteriaScore->toArray());
    }

    /** @test */
    public function canAddCategoryJudge()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $category = factory(Category::class)->create();

        $contest = $category->contest->first();

        $judge = factory(Judge::class)->create([
            'contest_id' => $contest->id,
        ]);

        // Act
        $manager->addCategoryJudge($judge);

        // Assert
        $this->assertDatabaseHas('category_judges', $category->categoryJudges()->where(['judge_id' => $judge->id])->first()->toArray());
    }

    /** @test */
    public function canRemoveCategoryJudge()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $contest = $this->contestFactory();

        $category = $contest->categories()->first();

        $categoryJudge = $category->categoryJudges()->first();

        $categoryContestant = $category->categoryContestants()->first();

        $criteria = $category->criterias()->first();

        $score = $this->faker->numberBetween(1, $criteria->percentage);

        $manager->loginJudge($categoryJudge->judge()->first());

        $criteriaScore = $manager->setScore($categoryContestant, $criteria, $score);

        $categoryScore = $criteriaScore->categoryScore()->first();

        // Act
        $manager->removeCategoryJudge($categoryJudge);

        // Assert
        $this
            ->assertDatabaseMissing('criteria_scores', $criteriaScore->toArray())
            ->assertDatabaseMissing('category_scores', $categoryScore->toArray())
            ->assertDatabaseMissing('category_judges', $categoryJudge->toArray());
    }

    /** @test */
    public function canAddCategoryContestant()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $category = factory(Category::class)->create();

        $contest = $category->contest()->first();

        $contestant = factory(Contestant::class)->create([
            'contest_id' => $contest->id,
        ]);

        // Act
        $manager->addCategoryContestant($contestant);

        // Assert
        $this->assertDatabaseHas('category_contestants', $category->categoryContestants()->where(['contestant_id' => $contestant->id])->first()->toArray());
    }

    /** @test */
    public function canRemoveCategoryContestant()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $contest = $this->contestFactory();

        $category = $contest->categories()->first();

        $categoryJudge = $category->categoryJudges()->first();

        $categoryContestant = $category->categoryContestants()->first();

        $criteria = $category->criterias()->first();

        $score = $this->faker->numberBetween(1, $criteria->percentage);

        $manager->loginJudge($categoryJudge->judge()->first());

        $criteriaScore = $manager->setScore($categoryContestant, $criteria, $score);

        $categoryScore = $criteriaScore->categoryScore()->first();

        // Act
        $manager->removeCategoryContestant($categoryContestant);

        // Assert
        $this
            ->assertDatabaseMissing('criteria_scores', $criteriaScore->toArray())
            ->assertDatabaseMissing('category_scores', $categoryScore->toArray())
            ->assertDatabaseMissing('category_contestants', $categoryContestant->toArray());
    }

    /** @test */
    public function canSetScore()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $contest = $this->contestFactory();

        $category = $contest->categories()->first();

        $criteria = $category->criterias()->first();

        $categoryJudge = $category->categoryJudges()->first();

        $categoryContestant = $category->categoryContestants()->first();

        $manager->loginJudge($categoryJudge->judge()->first());

        $score = $this->faker->numberBetween(1, $criteria->percentage);

        // Act
        $manager->setScore($categoryContestant, $criteria, $score);

        // Assert
        $categoryScore = $category->categoryScores()->where([
            'category_id' => $category->id,
            'category_judge_id' => $categoryJudge->id,
            'category_contestant_id' => $categoryContestant->id,
        ])->first();

        $this
            ->assertDatabaseHas('criteria_scores', CriteriaScore::where([
                'category_score_id' => $categoryScore->id,
                'criteria_id' => $criteria->id,
                'score' => $score,
            ])->first()->toArray())
            ->assertDatabaseHas('category_scores', $categoryScore->toArray());
    }

    /** @test */
    public function canCompleteScore()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $contest = $this->contestFaker(1, 1, 1, 1, 'scoring');

        $category = $contest->categories()->first();

        $categoryJudge = $category->categoryJudges()->first();

        $manager->loginJudge($categoryJudge->judge()->first());

        // Act
        $response = $manager->completeScore($categoryJudge);

        // Assert
        $this->assertDatabaseHas('category_judges', $categoryJudge->toArray());
    }

    /** @test */
    public function canCreateCategoryFromScore()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $contest = $this->contestFaker(1, 5, 1, 1, 'done');

        $category = $contest->categories()->first();

        $contestant = $contest->contestants()->first();

        $judge = $contest->judges()->first();

        $data = collect(factory(Category::class)->make(['contest_id' => null]))->except('contest_id')->all();

        $data['contestant_count'] = 3;

        $data['include_judges'] = 1;

        // Act
        $response = $manager->createCategoryFromScore($category, $data);

        // Assert
        $this
            ->assertDatabaseHas('categories', $response->toArray())
            ->assertDatabaseHas('category_judges', ['category_id' => $response->id, 'judge_id' => $judge->id, 'completed' => 0]);

        $categoryContestants = collect($manager->getScoredCategoryContestants($category)->toArray())->values()->all();

        foreach ($categoryContestants as $index => $categoryContestant) {
            if ($index < $data['contestant_count']) {
                $this->assertDatabaseHas('category_contestants', ['category_id' => $response->id, 'contestant_id' => $categoryContestant['contestant_id']]);
            } else {
                $this->assertDatabaseMissing('category_contestants', ['category_id' => $response->id, 'contestant_id' => $categoryContestant['contestant_id']]);
            }
        }
    }

    /** @test */
    public function canCreateContestFromScore()
    {
        // Arrange
        $this->seed();

        $manager = new ContestManager();

        $contest = $this->contestFaker(1, 5, 1, 1, 'done');

        $judge = $contest->judges()->first();

        $data = factory(Contest::class)->make([
            'logo' => UploadedFile::fake()->image('image.png', 8),
        ])->toArray();

        $data['contestant_count'] = 3;

        $data['include_judges'] = 1;

        // Act
        $response = $manager->createContestFromScore($contest, $data);

        // Assert
        $this
            ->assertDatabaseHas('contests', $response->toArray())
            ->assertDatabaseHas('judges', ['contest_id' => $response->id, 'user_id' => $judge->user_id]);

        $contestants = collect($manager->getScoredContestants($contest)->toArray())->values()->all();

        foreach ($contestants as $index => $contestant) {
            $contestant = collect($contestant)->only(['name', 'description', 'number'])->put('contest_id', $response->id)->all();

            if ($index < $data['contestant_count']) {
                $this->assertDatabaseHas('contestants', $contestant);
            } else {
                $this->assertDatabaseMissing('contestants', $contestant);
            }
        }
    }
}
