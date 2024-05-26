# laravel-segitseg

## Api
### php artisan install:api
Create Api folder in Controllers and paste this in it:
```
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::all();
        return response()->json($activities);
    }

    public function show($id)
    {
        $activity = Activity::findOrFail($id);
        return response()->json($activity);
    }

    public function search(Request $request)
    {
        // Implement search logic based on request parameters
    }
}
```

After that create the roures in the api.php file:
```
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/activities', 'App\Http\Controllers\Api\ActivityController@index');
Route::get('/activities/{id}', 'App\Http\Controllers\Api\ActivityController@show');

//You can use this too if you want
//Route::apiResource('activities', ActivityController::class);

```



Fast Modeling 
```
php artisan make:model Blog -c -f -m -s -r
```

Kép feltöltése, rátöltése és törlése
```
public function store()
    {
        request()->validate([
            'title' => ['required'],
            'description' => ['required'],
            'content' => ['required'],
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'

        ]);


        Blog::create([
            'author_id' => Auth::user()->id,
            'title' => request('title'),
            'description' => request('description'),
            'content' => request('content'),
            'image_url' => request('image_url')->store('images', 'public') // Store in public/images
        ]);

        return redirect('/blogs')->with('success', 'Blog created successfully!');
    }
```
```
public function update(Blog $blog)
    {
        $validated = request()->validate([
            'title' => ['required'],
            'description' => ['required'],
            'content' => ['required'],
            'image_url' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $validated['author_id'] = Auth::user()->id;
        $validated['image_url'] = request('image_url')->store('images', 'public'); // Store in public/images

        Storage::disk('public')->delete($blog->image_url);

        $blog->update($validated);

        return redirect('/blogs/' .  $blog->id )->with('success', 'Blog edited successfully"!');
    }
```
```
public function update(Todo $todo)
    {
        $validated = request()->validate([
            'title' => ['required', 'string', 'max:255'], // Title must be required, string, and under 255 characters
            'deadline' => ['required', 'date', 'after:today'], // Deadline must be required, a date, and after today
            'done' => ['required', 'boolean'], // Done flag must be required and a boolean value
            'category' => ['required', 'string', 'in:alacsony,közepes,magas'], // Category must be required, string, and one of the specified options
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Existing image validation

        ]);

        //replace image
        if (request()->has('image')) {
            if ($todo->image_url) {
                Storage::disk('public')->delete($todo->image_url);
            }
        }
        if (isset($validated['image_url'])) {
            $validated['image_url'] = request('image_url')->store('images', 'public');
            Storage::disk('public')->delete($todo->image_url);
        }

        //if done checked, update done_at
        if($validated['done'] === true) {
            $validated['done_at'] = time();
        }

        $todo->update($validated);

        return redirect('/todos/' .  $todo->id)->with('success', 'todo edited successfully!');
    }

```
```
public function destroy(Book $book)//: RedirectResponse
    {
        if ($book->image_url) {
            Storage::disk('public')->delete($book->image_url);
        }

        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Sikeres törlés');
    }

```
Képek előkészítése
```
create 'images' folder in storage/app/public/images

php artisan storage:link

Kép mutatásához ---> <img src="{{ str_starts_with($todo->image_url, 'http') ? $todo->image_url : '/storage/' . $todo->image_url}}" alt="{{$todo->title}}" style="width: 100px">

```


Rescrict for user only showing own profile and own stuff

```
Gate::define('edit-user', function (User $user, User $profileUser) {
            return $user->id === $profileUser->id;
        });
```

