<x-app-layout>
  <x-slot:title>パスワード変更</x-slot:title>
  <step-app
    :data="{{ json_encode(array_merge($data, $commonData, $memberFormData, array('errors' => $errors->getMessages())))}}"
  ></step-app>
</x-app-layout>