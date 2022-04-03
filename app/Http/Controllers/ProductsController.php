<?php

namespace App\Http\Controllers;

use App\DataTables\OrdersDataTable;
use App\DataTables\ProductsDataTable;
use App\Http\Requests\NewOrderRequest;
use App\Http\Requests\NewProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return bool|\Illuminate\Auth\Access\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(ProductsDataTable $dataTable)
    {
        return $dataTable->render('product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return bool|\Illuminate\Auth\Access\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('product.new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\NewProductRequest  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(NewProductRequest $request)
    {
        Product::create($request->validated());

        Session::flash('message','Product was successfully created');
        Session::flash('m-class','alert-success');
        return redirect('product');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return bool|\Illuminate\Auth\Access\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $product = Product::find($id);
        return view('product.edit')->with('product', $product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function update(NewProductRequest $request, $id)
    {
        $product = Product::find($id);
        $product->update($request->validated());

        Session::flash('message','Product was successfully updated');
        Session::flash('m-class','alert-success');

        return redirect('product');
    }


    public function delete(Request $request)
    {
        $product = Product::find($request->id);
        $product->delete_reason = $request->comment;
        $product->delete();
        $product->save();

        Session::flash('message','Order was successfully cancelled');
        Session::flash('m-class','alert-success');

        return response()->json([
            'status' => 200,
            'message' => 'Product Successfully Deleted'
        ]);
    }
}
