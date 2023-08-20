<?php
    namespace App\Http\ViewComposers;

    use \Illuminate\Contracts\View\View;

    use App\Models\Category;
    use App\Models\Challenge;
    use App\Models\Step;
    use App\Models\User;

    //ビューに渡すデータ(STEPを表示する際に必要なデータ)
    class StepDataComposer {
      public function compose(View $view){
        $categories = Category::select('id', 'name')->orderBy('kana')->get()->all();
        $view->with('stepData', [
          'categories' => $categories,
          'units' => Step::UNITS,
          'constant' => Step::CONSTANT,
          'columns' => Step::COLUMNS,
          'chalColumns' => Challenge::COLUMNS,
          'userColumns' => User::COLUMNS
        ]);
      }
    }