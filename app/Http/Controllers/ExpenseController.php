<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\HasApiTokens;
use App\Models\User; // Import the User model

class ExpenseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'receipt' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048', // Adjust max file size and allowed file types as needed
        ]);

        // Upload receipt
        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('uploads/expense/', $filename);
        }

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
        } else {
            $expense = new Expense();
        }

        $expense->amount = $amount;
        $expense->date = $date;
        $expense->payment = $paymentMethod;
        $expense->maincategory = $category;
        $expense->merchantname = $merchantName;
        $expense->notes = $notes;
        $expense->uid = $request->user()->id;
        $expense->receipt = $filename ?? null; // Make sure to handle case when filename is not set
        $expense->save();

        return response()->json(['success' => true, 'message' => 'Expense added successfully']);
    }
    
    public function listing(Request $request)
    {
        $expenses = Expense::where('uid', $request->user()->id)->get();
        return response()->json($expenses);
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'payment' => 'required|string',
            'maincategory' => 'required|string',
            'merchantname' => 'required|string',
            'notes' => 'nullable|string',
            // Add validation rules for other updated fields
        ]);
    
        // Find the expense by id
        try {
            $expense = Expense::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case where the expense with the given ID is not found
            return response()->json(['success' => false, 'message' => 'Expense not found'], 404);
        }
    
        // Update the expense data
        $expense->amount = $request->input('amount');
        $expense->date = $request->input('date');
        $expense->payment = $request->input('payment');
        $expense->maincategory = $request->input('maincategory');
        $expense->merchantname = $request->input('merchantname');
        $expense->notes = $request->input('notes');
        // Update other fields accordingly
    
        // Save the updated expense
        $expense->save();
    
        // Return a success response
        return response()->json(['success' => true, 'message' => 'Expense updated successfully']);
    }

    public function destroy($id)
{
    try {
        $expense = Expense::findOrFail($id);
        $expense->delete();
        return response()->json(['success' => true, 'message' => 'Expense deleted successfully']);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // If the expense has already been deleted, return success message
        return response()->json(['success' => true, 'message' => 'Expense has already been deleted'], 200);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}



   // In ExpenseController.php or your relevant controller file

public function getFilteredExpenseData(Request $request)
{
    try {
        $category = $request->input('category', ''); // Get the category from the request

        // Query the database with the category filter
        $expenses = Expense::when($category, function ($query) use ($category) {
            return $query->where('maincategory', $category); // Assuming 'maincategory' is the correct column name
        })->get();

        return response()->json($expenses);
    } catch (\Exception $e) {
        // Handle exceptions if any
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}
