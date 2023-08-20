<x-app-layout>
  <x-slot:title>{{$data['contributor'][$stepData['userColumns']['name']]}}</x-slot:title>
  <step-app
    :data="{{ json_encode(array_merge($data, $commonData, $stepData)) }}"
  ></step-app>
</x-app-layout>