<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Support\Facades\Session;

class ExpenseController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([
            'receipt' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048', // Adjust max file size and allowed file types as needed
        ]);
        if ($request->hasFile('receipt')) {
            // Upload receipt
            $file = $request->file('receipt');
            $extension=$file->getClientOriginalExtension();

            $filename=time().''.$extension;
            $file->move('uploads/expense/',$filename);
        }
        $amount = $request->input('amount');
        $date = $request->input('date');
        $paymentMethod = $request->input('payment');
        $category = $request->input('maincategory');
        $otherCategory = $request->input('othersTextBoxContainer');
        $merchantName = $request->input('merchantname');
        $notes = $request->input('notes');
        $receipt = $request->input('receipt');

        if ($category == 'Others' && !empty($otherCategory)) {
            $newCategory = new Category();
            $newCategory->name = $otherCategory;
            $newCategory->save();

            $cid = $newCategory->id;

            $expense = new Expense();
            $expense->cid = $cid;
            $expense->amount = $amount;
            $expense->date = $date;
            $expense->payment = $paymentMethod;
            $expense->maincategory = $category;
            $expense->merchantname = $merchantName;
            $expense->notes = $notes;
            $expense->receipt = $filename;
            $expense->save();

            return response()->json(['success' => true, 'message' => 'Expense added successfully']);
        } else {
            $expense = new Expense();
            $expense->amount = $amount;
            $expense->date = $date;
            $expense->payment = $paymentMethod;
            $expense->maincategory = $category;
            $expense->merchantname = $merchantName;
            $expense->notes = $notes;
            $expense->receipt = $filename;
            $expense->save();

            return response()->json(['success' => true, 'message' => 'Expense added successfully']);
       }
   }
}
