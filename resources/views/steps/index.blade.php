<x-app-layout>
  <x-slot:title>@if(empty($current) || $current === 1) 投稿されたSTEP @else {{$current}}ページ目 - 投稿されたSTEP @endif</x-slot:title>
  <step-app
    :data="{{ json_encode(array_merge($data, $commonData, $stepData)) }}"
  ></step-app>
</x-app-layout>