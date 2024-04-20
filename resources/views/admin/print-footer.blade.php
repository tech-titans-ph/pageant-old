<div class="m-3 text-xs">
  <p class="italic text-center">***Nothing Follows***</p>
  <br />
  <div class="flex justify-between"
    style="page-break-inside: avoid;">
    <div class="flex-shrink-1">
      <div>Tabulated By:</div>
      <br />
      <div class="px-4 py-1 text-center border-t border-black">
        <div class="font-bold">NAME</div>
        <div>{{ Str::title(config('app.company')) }}</div>
      </div>
    </div>
    <div class="flex-shrink-1">
      <div>In witness thereof:</div>
      <br />
      <div class="px-4 py-1 text-center border-t border-black">
        <div class="font-bold">NAME</div>
        <div>{{ Str::title(config('app.witness')) }}, Representative</div>
      </div>
    </div>
  </div>
  <br />
  <div style="page-break-inside: avoid;">
    <div>
      <div>Approved By:</div>
      <br />
      <div class="grid grid-cols-3 gap-4 place-items-center justify-items-center">
        @foreach ($model->judges as $judge)
          <div>
            <div class="px-4 font-bold text-center uppercase border-b border-black">{{ $judge->name }}</div>
            <div class="text-center">Board of Judges</div>
          </div>
        @endforeach
      </div>
    </div>
    <br />
    <br />
    <div class="flex justify-center ">
      <div class="px-16 text-center border-t border-black">Head Coordinator</div>
    </div>
  </div>
  <br />
  <div style="page-break-inside: avoid;">
    <table>
      <tr>
        <td class="px-2">Date Generated:</td>
        <td class="px-2 border-b border-black">{{ now()->format('m/d/Y') }}</td>
      </tr>
      <tr>
        <td class="px-2">Time Generated:</td>
        <td class="px-2 border-b border-black">{{ now()->format('h:i A') }}</td>
      </tr>
    </table>
    <br />
    <div class="font-bold text-center text-red-600">
      <p>NOTE: This is the official tabulation of the {{ $model->name }}. Erasures with the use of the any medium is prohibited.</p>
      <p>This paper must not be tampered; therefore, any amendment is not
        allowed. Strictly, this is the final result.</p>
    </div>
    <br />
    <div style="page-break-inside: avoid;">
      <div class="flex items-center justify-center">
        <div class="text-xl">&copy;</div>
        <img src="{{ asset('images/' . env('LOGO_TEXT_BELOW')) }}"
          class="h-24">
        <div class="mt-1 text-center">{{ now()->format('Y') }}</div>
      </div>
    </div>
  </div>
</div>
