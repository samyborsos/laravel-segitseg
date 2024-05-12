# laravel-segitseg

Fast Modeling 
```
php artisan make:model Blog -c -f -m -s -r
```

Kép feltöltése, rátöltése és törlése
```
public function update(BookRequest $request, Book $book): RedirectResponse
    {
        $validated = $this->getValidated($request);

        if ($request->has('image')) {
            if ($book->image_url) {
                Storage::disk('images')->delete($book->image_url);
            }
        }

        if (isset($validated['delete-image'])) {
            Storage::disk('images')->delete($book->image_url);
            $validated['image_url'] = null;
        }

        $book->update($validated);

        // visszairányítás
        return redirect()->route('admin.books.index')->with('success', 'Sikeres mentés');
    }



public function destroy(Book $book)//: RedirectResponse
    {
        if ($book->image_url) {
            Storage::disk('images')->delete($book->image_url);
        }

        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Sikeres törlés');
    }
```

Rescrict for user only showing own profile and own stuff

```
Gate::define('edit-user', function (User $user, User $profileUser) {
            return $user->id === $profileUser->id;
        });
```

