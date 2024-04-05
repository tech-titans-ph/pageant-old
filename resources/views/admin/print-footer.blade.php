<div class="m-10 space-y-10">
  <p class="italic text-center">***Nothing Follows***</p>
  <div class="flex justify-between"
    style="page-break-inside: avoid;">
    <div class="flex-shrink-1">
      <div>Tabulated By:</div>
      <div class="px-4 py-1 mt-10 text-center border-t-2">
        <div class="font-bold">NAME</div>
        <div>{{ Str::title(config('app.company')) }}</div>
      </div>
    </div>
    <div class="w-1/3">&nbsp;</div>
    <div class="flex-shrink-1">
      <div>In witness thereof:</div>
      <div class="px-4 py-1 mt-10 text-center border-t-2">
        <div class="font-bold">NAME</div>
        <div>{{ Str::title(config('app.witness')) }}, Representative</div>
      </div>
    </div>
  </div>
  <div class="space-y-10"
    style="page-break-inside: avoid;">
    <div>
      <div class="mb-10">Approved By:</div>
      <div class="grid grid-cols-2 gap-8 justify-items-center">
        @foreach ($model->judges as $judge)
          <div>
            <div class="font-bold text-center uppercase">{{ $judge->name }}</div>
            <div class="px-8 py-1 text-center border-t-2">Board of Judges</div>
          </div>
        @endforeach
      </div>
    </div>
    <div class="flex justify-center">
      <div class="px-8 py-1 text-center border-t-2">Head Coordinator</div>
    </div>
  </div>
  <div class="space-y-10"
    style="page-break-inside: avoid;">
    <table>
      <tr>
        <td class="px-2 py-1">Date Generated:</td>
        <td class="px-2 border-b">{{ now()->format('m/d/Y') }}</td>
      </tr>
      <tr>
        <td class="px-2 py-1">Time Generated:</td>
        <td class="px-2 border-b">{{ now()->format('h:i A') }}</td>
      </tr>
    </table>
    <div class="font-bold text-center text-red-600">
      <p>NOTE: This is the official tabulation of the {{ $model->name }}. Erasures with the use of the any medium is prohibited.</p>
      <p>This paper must not be tampered; therefore, any amendment is not
        allowed. Strictly, this is the final result.</p>
    </div>
    <div class="flex items-center justify-center">
      <div class="text-xl">&copy;</div>
      <img src="{{ asset('images/' . env('LOGO_TEXT_BELOW')) }}"
        class="h-40">
    </div>
    <div class="text-center">{{ now()->format('Y') }}</div>
  </div>
</div>
