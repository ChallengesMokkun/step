<x-app-layout>
  <x-slot:title>{{ $data['dbData'][$stepData['columns']['title']] }}</x-slot:title>
  <step-app
    :data="{{ json_encode(array_merge($data, $commonData, $stepData)) }}"
  ></step-app>
</x-app-layout>