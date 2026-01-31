<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/invoice_pdf/{record}', [PDFController::class, 'invoicepdf'])->name('invoicepdf.report');
Route::get('/receipt/{record}', [PDFController::class, 'receipt'])->name('receipt.report');
Route::get('/quotation/{record}', [PDFController::class, 'quotation'])->name('quotation.report');
