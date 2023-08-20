<x-app-layout>
  <x-slot:title>パスワードリセット</x-slot:title>
  <step-app
    :data="{{ json_encode(array_merge($data, $commonData, $memberFormData, array('errors' => $errors->getMessages())))}}"
  ></step-app>
</x-app-layout>