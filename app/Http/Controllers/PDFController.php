<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Settings;
use App\Models\product;
use App\Models\Invoiceproduct;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use App\Models\receipt;
use App\Models\Quotation;
use App\Models\quoteproducts;

class PDFController extends Controller
{
     public function invoicepdf($record)
    { 
          $data=[];
       // dd($record);
        $inv_data = Invoice::where('id',$record)->get();
        $other_des=[];
        if (!$inv_data) {
            abort(404, 'Invoice not found.');
        }
         $data=$inv_data->toArray();
        $client=$data[0]['cust_id'];
        $cus = Customer::where('id',$client)->get();
        $cust=$cus->toArray();
        $data[0]['cust_id']=$cust[0]['id'];
        $data[0]['cust_name'] = $cust[0]['name'];
        $data[0]['cust_email'] = $cust[0]['email'];
        $data[0]['cust_address'] = $cust[0]['address'];
        $data[0]['cust_ph'] = $cust[0]['phone_no_one'];      
        $data[0]['image']=asset('images/saklogo-mi.jpg');
        $setting= Settings::all();
        $set=$setting->toArray();
        $data[0]['company_address']=$set[0]['cmpny_address'];
        $data[0]['company_email']=$set[0]['cmpny_email'];
        $data[0]['company_phone']=$set[0]['cmpny_phone'];
        $data[0]['company_name']=$set[0]['cmpny_name'];
        $invDe= Invoiceproduct::where('invoice_id',$record)->get();
        $invDes = $invDe->toArray();
        $prod = product::where('id',$invDes[0]['product_id'])->get();
        $product=$prod->toArray();
        for($i=0; $i< count($invDes); $i++){
            $prod = product::where('id',$invDes[$i]['product_id'])->get();
            $product=$prod->toArray();
            $other_des[$i]['prod_name']=$product[0]['prod_name'];
            $other_des[$i]['quantity']=$invDes[$i]['quantity'];
            $other_des[$i]['unit']=$invDes[$i]['unit'];
            $other_des[$i]['price']=$invDes[$i]['price'];
            $other_des[$i]['total_price']=$invDes[$i]['total_price'];
        }
        $invoicedes=$other_des;

        $pdf = Pdf::loadView('pdf.invoice', ['invoice' => $data[0], 'invoicedes' => $invoicedes]);
        return $pdf->stream('invoice.pdf');
    }
     public function receipt($record)
    {
            $data=[];
         // dd($record);
            $rec_data = receipt::where('id',$record)->get();
            if (!$rec_data) {
                abort(404, 'Receipt not found.');
            }
             $data=$rec_data->toArray();
            $inv_num=$data[0]['inv_num'];
            $inv_data = Invoice::where('id',$inv_num)->get();
            $inv=$inv_data->toArray();
            $data[0]['inv_no']=$inv[0]['inv_no'];
            $setting= Settings::all();
        $set=$setting->toArray();
        $data[0]['company_address']=$set[0]['cmpny_address'];
        $data[0]['company_email']=$set[0]['cmpny_email'];
        $data[0]['company_phone']=$set[0]['cmpny_phone'];
        $data[0]['company_name']=$set[0]['cmpny_name'];
$client=$data[0]['cust_id'];
        $cus = Customer::where('id',$client)->get();
        $cust=$cus->toArray();
        $data[0]['cust_id']=$cust[0]['id'];
        $data[0]['cust_name'] = $cust[0]['name'];
        $data[0]['cust_email'] = $cust[0]['email'];
        $data[0]['cust_address'] = $cust[0]['address'];
        $data[0]['cust_ph'] = $cust[0]['phone_no_one'];
            $pdf = Pdf::loadView('pdf.receipt', ['receipt' => $data[0]]);
            return $pdf->stream('receipt.pdf');
    }
    public function quotation($record)
    {
        $data = [];
        $quote_data = Quotation::where('id', $record)->get();
        if (!$quote_data) {
            abort(404, 'Quotation not found.');
        }
        $data = $quote_data->toArray();
        $client = $data[0]['quotecust_id'];
        $cus = Customer::where('id', $client)->get();
        $cust = $cus->toArray();
        $data[0]['cust_id'] = $cust[0]['id'];
        $data[0]['cust_name'] = $cust[0]['name'];
        $data[0]['cust_email'] = $cust[0]['email'];
        $data[0]['cust_address'] = $cust[0]['address'];
        $data[0]['cust_ph'] = $cust[0]['phone_no_one'];
        $setting= Settings::all();
        $set=$setting->toArray();
        $data[0]['company_address']=$set[0]['cmpny_address'];
        $data[0]['company_email']=$set[0]['cmpny_email'];
        $data[0]['company_phone']=$set[0]['cmpny_phone'];
        $data[0]['company_name']=$set[0]['cmpny_name'];

        $quoteDe= quoteproducts::where('quotation_id',$record)->get();
        $quoteDes = $quoteDe->toArray();
       
        for($i=0; $i< count($quoteDes); $i++){
            $prod = product::where('id',$quoteDes[$i]['quoteprod_id'])->get();
            $product=$prod->toArray();
            $other_des[$i]['prod_name']=$product[0]['prod_name'];
            $other_des[$i]['quantity']=$quoteDes[$i]['quote_quantity'];
            $other_des[$i]['unit']=$quoteDes[$i]['quote_unit'];
            $other_des[$i]['price']=$quoteDes[$i]['quote_price'];
            $other_des[$i]['total_price']=$quoteDes[$i]['quotetotal_price'];
        }
           $pdf = Pdf::loadView('pdf.quotation', ['quotation' => $data[0], 'quote_items' => $other_des]);
            return $pdf->stream('quotation.pdf');
    }
}