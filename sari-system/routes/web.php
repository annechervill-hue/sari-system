use App\Http\Controllers\POSController;

Route::get('/', [POSController::class, 'index']);
Route::get('/products', [POSController::class, 'products']);
Route::post('/sale', [POSController::class, 'storeSale']);
Route::get('/reports', [POSController::class, 'reports']);
Route::put('/products/{id}/price', [POSController::class, 'updatePrice']);