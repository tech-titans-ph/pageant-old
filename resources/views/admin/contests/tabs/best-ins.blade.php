<tab-item title="Best Ins">
  <ul>
    <li class="p-4">
      <form action="{{ route('admin.contests.bestins.store', ['contest' => $contest->id, 'activeTab' => 'Best Ins']) }}"
        method="post"
        id="best-in-form">
        @csrf

        <input type="hidden"
          id="type_id"
          name="type_id"
          value="{{ old('type_id') }}" />
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
          @formField(['label' => 'Type', 'error' => 'type'])
          {!! Form::select(
              'type',
              collect(['category', 'criteria'])->mapWithKeys(function ($item) {
                  return [$item => Str::title($item)];
              }),
              old('type'),
              [
                  'id' => 'type',
                  'class' => 'block w-full form-select capitalize',
                  'placeholder' => '- Select Type -',
              ],
          ) !!}
          @endformField

          <div>
            @formField(['label' => 'Category', 'error' => 'type_id', 'class' => 'type-wrapper category-type-wrapper ' . (old('type') == 'category' ? '' : 'hidden')])
            {!! Form::select('category_type_id', $contest->categories->pluck('name', 'id')->all(), old('type') == 'category' ? old('type_id') : null, [
                'id' => 'category_type',
                'class' => 'block w-full form-select type_id',
                'placeholder' => '- Select Category -',
            ]) !!}
            @endformField

            @formField(['label' => 'Criteria', 'error' => 'type_id', 'class' => 'type-wrapper criteria-type-wrapper ' . (old('type') == 'criteria' ? '' : 'hidden')])
            {!! Form::select(
                'criteria_type_id',
                $contest->categories->mapWithKeys(function ($item, $key) {
                    return [$item->name => $item->criterias->pluck('name', 'id')];
                }),
                old('type') == 'criteria' ? old('type_id') : null,
                [
                    'id' => 'criteria_type',
                    'class' => 'block w-full form-select type_id',
                    'placeholder' => '- Select Criteria -',
                ],
            ) !!}
            @endformField
          </div>

          @formField(['label' => 'Name', 'error' => 'name'])
          <input type="text"
            id="bestin_name"
            name="name"
            value="{{ old('name') }}"
            class="block w-full form-input"
            placeholder="Enter name..." />
          @endformField
        </div>
        <div>
          @button(['type' => 'submit', 'class' => 'flex-none'])
          Add Best In
          @endbutton
        </div>
      </form>
    </li>

    @if ($contest->bestins->count())
      <li class="p-4 border-t">
        @buttonLink([
            'href' => route('admin.contests.bestins.index', ['contest' => $contest->id]),
            'attributes' => 'target=_blank'
        ])
          Print Scores
        @endbuttonLink
      </li>
    @endif

    @forelse($contest->bestins as $bestin)
      @php
        $group = $bestin->group()->first();

        $parent = $group->{['category' => 'contest', 'criteria' => 'category'][$bestin->type]}()->first();
      @endphp

      <li class="flex items-start justify-between p-4 border-t">
        <div class="space-y-2">
          <div>Best in {{ $bestin->name }}</div>
          <div>{{ $group->name }} <span class="capitalize">{{ $bestin->type }}</span></div>
          <div>
            {{ $group->max_points_percentage }} {{ $parent->scoring_system == 'average' ? '%' : 'points' }}
          </div>
        </div>
        <form action="{{ route('admin.contests.bestins.destroy', ['contest' => $contest->id, 'bestin' => $bestin->id]) }}"
          method="post">
          @csrf
          @method('DELETE')

          <input type="hidden"
            name="activeTab"
            value="{{ request()->get('activeTab') }}" />

          @button(['type' => 'submit', 'color' => 'danger'])
          Remove
          @endbutton
        </form>
      </li>
    @empty
      <li class="p-4 border-t">No available Best In.</li>
    @endforelse
  </ul>
</tab-item>

@push('scripts')
  <script type="text/javascript">
    window.addEventListener('load', () => {
      var type = document.querySelector('#type');

      var type_id = document.querySelector('#type_id');

      var bestinName = document.querySelector('#bestin_name');

      var bestinForm = document.querySelector('#best-in-form');


      document.querySelector('#type').addEventListener('input', event => {
        document.querySelectorAll('.type-wrapper').forEach((tag) => {
          tag.classList.add('hidden');
        });

        var selector = '.' + event.target.value + '-type-wrapper';

        document.querySelector(selector)?.classList?.remove('hidden');
      });

      document.querySelectorAll('.type_id').forEach(tag => {
        tag.addEventListener('change', event => {
          type_id.value = '';

          var text = tag.options[tag.selectedIndex].text;

          type_id.value = event.target.value;

          bestinName.value = text;
        });
      });
    });
  </script>
@endpush
