<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;


Route::get('/', [ContactController::class, 'index'])->name('contacts.index');
Route::get('contacts/create', [ContactController::class, 'create'])->name('contacts.create');
Route::post('contacts', [ContactController::class, 'store'])->name('contacts.store');

Route::get('contacts/merge/{id}', [ContactController::class, 'showMergeModal'])->name('contacts.merge.modal');
Route::post('contacts/merge', [ContactController::class, 'mergeContacts'])->name('contacts.merge');
