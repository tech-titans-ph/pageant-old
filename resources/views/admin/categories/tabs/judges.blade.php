<tab-item title="Judges">
  <ul>
    @forelse ($category->judges as $key => $judge)
      <li class="p-4 {{ $key ? 'border-t' : '' }}">
        <div class="flex items-center space-x-4">
          <div class="flex-grow">
            <div class="font-bold">{{ $judge->name }}</div>
            <div class="mt-2">
              <span class="px-2 rounded font-normal {{ $judge->pivot->completed ? 'bg-green-300 text-green-900' : 'bg-blue-500 text-blue-100' }}">{{ $judge->completed ? 'Completed Scoring' : 'Added' }}</span>
            </div>
          </div>
          <div class="flex items-center flex-none space-x-2 whitespace-no-wrap">
            <form method="post"
              action="{{ route('admin.contests.categories.judges.move.up', ['contest' => $contest->id, 'category' => $category->id, 'judge' => $judge->id]) }}"
              class="inline-block btn">
              @csrf
              @method('PATCH')

              @button(['type' => 'submit']) Move Up @endbutton
            </form>

            <form method="post"
              action="{{ route('admin.contests.categories.judges.move.down', ['contest' => $contest->id, 'category' => $category->id, 'judge' => $judge->id]) }}"
              class="inline-block btn">
              @csrf
              @method('PATCH')

              @button(['type' => 'submit']) Move Down @endbutton
            </form>
            <form method="post"
              action="{{ route('admin.contests.categories.judges.destroy', ['contest' => $contest->id, 'category' => $category->id, 'judge' => $judge->id]) }}"
              class="inline-block btn @if ($judge->pivot->scores()->count()) remove-score-confirmation-form @endif">
              @csrf
              @method('DELETE')

              <input type="hidden"
                name="column"
                value="category_judge_id" />
              <input type="hidden"
                name="value"
                value="{{ $judge->pivot->id }}" />
              <input type="hidden"
                name="auth_password" />


              @if ($errors->{"category_judge_id_{$judge->pivot->id}"}->any())
                <div class="mb-1 text-sm italic text-red-500">{{ $errors->{"category_judge_id_{$judge->pivot->id}"}->first() }}</div>
              @endif

              @button(['type' => 'submit', 'color' => 'danger']) Remove @endbutton
            </form>
          </div>
        </div>
      </li>
    @empty
      <li class="p-4">
        No added judges.
      </li>
    @endforelse

    @foreach ($removedJudges as $judge)
      <li class="p-4 border-t">
        <div class="flex items-center">
          <div class="flex-grow">
            <div class="font-bold">{{ $judge->name }}</div>
            <div class="mt-2">
              <span class="px-2 font-normal text-red-100 bg-red-500 rounded">Removed</span>
            </div>
          </div>
          <div class="flex-none ml-4 whitespace-no-wrap">
            <form method="post"
              action="{{ route('admin.contests.categories.judges.store', ['contest' => $contest->id, 'category' => $category->id]) }}"
              class="inline-block btn">
              @csrf
              <input type="hidden"
                name="judge_id"
                value="{{ $judge->id }}">
              @button(['type' => 'submit']) Add @endbutton
            </form>
          </div>
        </div>
      </li>
    @endforeach
  </ul>
</tab-item>
