<x-app-layout>
  <x-slot:title>お問い合わせ</x-slot:title>
  <step-app
    :data="{{ json_encode(array_merge($data, $commonData, array('errors' => $errors->getMessages())))}}"
  ></step-app>
</x-app-layout>