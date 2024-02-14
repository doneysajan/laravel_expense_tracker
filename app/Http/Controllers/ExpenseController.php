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

       


        $amount = $request->input('amount');
        $date = $request->input('date');
        $paymentMethod = $request->input('payment');
        $category = $request->input('maincategory');
        $otherCategory = $request->input('othersTextBoxContainer');
        $merchantName = $request->input('merchantname');
        $notes = $request->input('notes');

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
            $expense->save();

            return response()->json(['success' => true, 'message' => 'Expense added successfully']);
       }
   }
}
