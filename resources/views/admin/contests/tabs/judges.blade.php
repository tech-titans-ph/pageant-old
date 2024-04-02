<tab-item title="Judges">
  <ul>
    <li class="p-4">
      <form method="post"
        action="{{ route('admin.contests.judges.store', ['contest' => $contest->id, 'activeTab' => 'Judges']) }}"
        class="flex flex-wrap items-start lg:flex-no-wrap">
        @csrf

        @formField(['error' => 'name', 'class' => 'block flex-grow lg:mr-4 mb-6 lg:mb-0'])
        <div>
          <input-picker api="{{ route('admin.judges.index') }}"
            hidden-name="name"
            display-name="name"
            hidden-property="name"
            display-property="name"
            hidden-value="{{ old('name') }}"
            display-value="{{ old('name') }}"
            placeholder="Enter name of judge..."></input-picker>
        </div>
        @endformField

        @button(['type' => 'submit', 'class' => 'flex-none'])
        Add Judge
        @endbutton
      </form>
    </li>

    @forelse ($contest->judges as $judge)
      <li class="p-4 border-t">
        <div class="flex flex-col items-start lg:flex-row lg:items-center">
          <a href="{{ route('admin.contests.judges.edit', ['contest' => $contest->id, 'judge' => $judge->id]) }}"
            class="flex-grow font-bold lg:pr-2">
            {{ $judge->name }}
          </a>
          <div class="flex justify-between flex-none w-full mt-4 space-x-2 whitespace-no-wrap lg:w-auto lg:mt-0">
            @buttonLink(['href' => route('admin.contests.judges.login', ['contest' => $contest->id, 'judge' => $judge->id])])
              Login
            @endbuttonLink

            <form method="post"
              action="{{ route('admin.contests.judges.move.up', ['contest' => $contest->id, 'judge' => $judge->id]) }}"
              class="inline-block btn">
              @csrf
              @method('PATCH')

              @button(['type' => 'submit']) Move Up @endbutton
            </form>

            <form method="post"
              action="{{ route('admin.contests.judges.move.down', ['contest' => $contest->id, 'judge' => $judge->id]) }}"
              class="inline-block btn">
              @csrf
              @method('PATCH')

              @button(['type' => 'submit']) Move Down @endbutton
            </form>

            <form method="post"
              action="{{ route('admin.contests.judges.destroy', ['contest' => $contest->id, 'judge' => $judge->id]) }}"
              class="inline-block btn">
              @csrf
              @method('DELETE')

              @button(['type' => 'submit', 'color' => 'danger'])
              Remove
              @endbutton
            </form>
          </div>
        </div>
      </li>
    @empty
      <li class="p-4 border-t">No available Judge(s).</li>
    @endforelse
  </ul>
</tab-item>
