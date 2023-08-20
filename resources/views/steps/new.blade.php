<x-app-layout>
  <x-slot:title>STEP登録</x-slot:title>
  <step-app
    :data="{{ json_encode(array_merge($data, $commonData, $stepData, array('errors' => $errors->getMessages())))}}"
  ></step-app>
</x-app-layout>