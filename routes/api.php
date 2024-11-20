Route::get('/states/{state}/lgas', function($state) {
    return \App\Models\LGA::where('state_id', $state)
        ->where('status', 'active')
        ->get(['id', 'name']);
});
