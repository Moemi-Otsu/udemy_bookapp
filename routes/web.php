<?php

use App\Book;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // $books変数に、Bookモデルから全てのデータを取得して格納する
    // $books変数に、データが入っていれば、セットされる
    $books = Book::all();
    // 以下に２つ目の引数として、[ 'books' => $books ]を設定
    return view('books', [ 'books' => $books ]);
});

// '/book'というルーティングの場合に、function以下の処理を行う
Route::post('/book', function(Request $request) {
    // 有効な変数が入っているかどうかを確認するための $validator 変数を定義する
    // Validatorクラスの、make というメソッドを使う
    // $requestから、all全てのデータを取得し、
    $validator = Validator::make($request->all(),[
        // name属性に条件を追加する
        // 本のタイトルをrequired（必須）にして、最大の文字数を設定する
        'name' => 'required|max:255',
    ]);

    if ($validator->fails()) {
        return redirect('/')
            ->withInput()
            ->withErrors($validator);
    }

    // $bookに新しいオブジェクトを作成する
    $book = new Book;
    // Bookモデルの新しいオブジェクト$bookのtitleカラムは、
    // 先ほど作成した $request に含まれる name を使う。
    $book->title = $request->name;
    // saveメソッドを呼び出し、$bookを保存する
    $book->save();

    // $bookを保存したら、最後にトップページにリダイレクトする
    return redirect('/');
});

// {book} <- このようにかくと、bookオブジェクトのID番号を返す、
// インプリシットバインディングという仕組みが機能して、IDの数字がここに入ってくる
// function(Book $book) <- Bookモデルのデータを、$book変数に渡してあげる
Route::delete('/book/{book}', function(Book $book) {
    $book->delete();

    // 削除処理が終わったら、TOPページにリダイレクトしてあげる
    return redirect('/');
});