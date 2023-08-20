<x-app-layout>
  <x-slot:title>@if(empty($current) || $current === 1) チャレンジ @else {{$current}}ページ目 - チャレンジ @endif</x-slot:title>
  <step-app
    :data="{{ json_encode(array_merge($data, $commonData, $stepData)) }}"
  ></step-app>
</x-app-layout>