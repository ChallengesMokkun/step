<x-app-layout>
  <x-slot:title>退会</x-slot:title>
  <step-app
    :data="{{ json_encode(array_merge($data, $commonData, $memberFormData, array('errors' => $errors->getMessages())))}}"
  ></step-app>
</x-app-layout>